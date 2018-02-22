<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 16:17
 */

namespace app\Controller;

use app\DAO\UserDAO;
use app\Entities\UserDTO;
use app\Libs\PasswordHash;
use app\Utils\Utils;
use Firebase\JWT\JWT;
use Psr\{
    Container\ContainerExceptionInterface, Container\ContainerInterface, Container\NotFoundExceptionInterface
};
use Slim\Http\Request;
use Slim\Http\Response;
use Tuupola\Base62;


class UserCtrl
{
    private $ctx, $password;

    /**
     * UserCtrl constructor.
     * @param $ctx
     */
    public function __construct(ContainerInterface $ctx)
    {
        $this->ctx = $ctx;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAllUsers(Request $request, Response $response)
    {
        $result = [];
        try {
            $users = $this->getUserDAO();
            $result = $users->findAll()->getAllAsArray();
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
        return $response->withJson($result);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getOneUser(Request $request, Response $response, array $args)
    {
        $result = [];
        $searchUser = [
            'name' => $args['login'] ?? null,
            //'pass' => $args['pass'] ?? null
        ];
        //var_dump($searchUser);
        try {
            $users = $this->getUserDAO();
            $result = $users->find($searchUser, [], [1]);

        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
        return $response->withJson($result);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function findUser(Request $request, Response $response, array $args)
    {

        $name = $args['name'] ?? null;
        $pass = $args['pass'] ?? null;

        // create curl resource
        $curl = curl_init();

        // set options and url
        $opts = [
            CURLOPT_URL => "http://findeur2017.findeur.fr/auth?user=$name&mdp=$pass",
            CURLOPT_RETURNTRANSFER => true,
        ];
        curl_setopt_array($curl, $opts);

        // $output contains the output string
        $output = curl_exec($curl);

        // close curl resource to free up system resources
        curl_close($curl);


        $arr = $this->returnArrayFromPartOfString($output, "\n<!--");

        return $response->withJson($arr);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function findUserPost(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();

        // create curl resource
        $curl = curl_init();

        $paramsTab = [
            'user' => $request->getParam("username") ?? null,
            'mdp' => $request->getParam('password') ?? null
        ];

        $params_string = http_build_query($paramsTab);

        // set options and url
        $opts = [
            CURLOPT_URL => "http://findeur2017.findeur.fr/auth",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params_string,
            CURLOPT_RETURNTRANSFER => true,
        ];
        curl_setopt_array($curl, $opts);

        // $output contains the output string
        $output = curl_exec($curl);

        // close curl resource to free up system resources
        curl_close($curl);


        $arr = $this->returnArrayFromPartOfString($output, "\n<!--");

        return $response->withJson($arr);
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response $response
     */
    public function findUserPost2(Request $request, Response $response) {
        // object for return of the function
        $result = ['success' => false, 'message' => 'Bad credentials'];

        // Retrieving query parameters and encrypting the password and generating the associated salt
        $requestParams = $request->getParams();

        $login = $requestParams['username'] ?? '';
        $search = [];
        $userPass = $requestParams['password'] ?? '';

        // creating the 'name' or 'email' key for the user's search
        $search = $this->usernameIsNameOrEmail($login, $search);

        // Now we can perform the search of user
        $user = $this->getUserDAO()
            ->findOneByNameOrEmail($search['name'] ?? '', $search['email'] ?? '')
            ->getOneAsArray();


        // if the user exists, check the new hash of his password with his salt
        if ($user != false) {
            $t_hasher = new PasswordHash(8, TRUE);
            $result['success'] = $t_hasher->CheckPassword($userPass, $user['pass']);
        }

        if ($result['success']) {
            $result['token'] = $this->getToken($request, $user['name']);
            unset($user['nonce']);
            unset($user['pass']);
            $result['user'] = $user;
            $result['message'] = 'Successful connection';
        }

        return $response->withJson($result);
//            ->withHeader('Access-Control-Allow-Origin', '*')
//            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }

    public function addUserPost(Request $request, Response $response)
    {
        // Retrieving query parameters and encrypting the password and generating the associated salt
        $requestParams = $request->getParams();
        $requestParams = $this->cryptPassAndNonceFromRequestParams($requestParams);

        // Give a default privilege for the user
        if (!is_numeric($requestParams['privs']) ||
            ($requestParams['privs'] != 4 && $requestParams['privs'] != 6)) {
            $requestParams['privs'] = 6;
        }
        
        $requestParams['lastAccess'] = 0;
        if (!isset($requestParams['realName']) || empty($requestParams['realName'])) {
            $requestParams['realName'] .= $requestParams['first_name'] ?? '';
            $requestParams['realName'] .= ' ';
            $requestParams['realName'] .= $requestParams['last_name'] ?? '';
        }

        // Hydrate the object that represents the user
        $userObj = $this->getUserDTO();
        $userObj->hydrate($requestParams);

        // Verification of required fields
        $msg = $this->verificationOfRequiredFields($userObj);

        // If there is no error message, persist data on DB
        if (empty($msg)) {
             $dao = $this->getUserDAO();
             $dao->save($userObj);
//                 $dao->save($userObj)->flush();
             $this->sendAnEmailViaTextpattern($userObj->getEmail() ?? '', $userObj->getName() ?? '',
                 $this->password, $userObj->getLastName() ?? '');
        }

        return $response->withJson($msg);
    }

    /**
     * @return UserDAO
     */
    private function getUserDAO()
    {
        $dao = null;
        try {
            $dao = $this->ctx->get('user.dao');
        } catch (ContainerExceptionInterface $exception) {
        }

        return $dao;
    }

    /**
     * @return UserDTO
     */
    private function getUserDTO()
    {
        $dto = null;
        try {
            $dto = $this->ctx->get('user.dto');
        } catch (ContainerExceptionInterface $exception) {
        }

        return $dto;
    }

    /**
     * @param $myString
     * @param $findMe
     * @return mixed
     */
    private function returnArrayFromPartOfString($myString, $findMe)
    {
        $pos = strpos($myString, $findMe);
        $res = substr($myString, 0, $pos);
        $arr = json_decode($res, true);
        return $arr;
    }

    private function returnArrayFromPartOfString2($myString, $findMe)
    {
        $pos = strpos($myString, $findMe);
        $res = substr($myString, 0, $pos);
        return $res;
    }

    /**
     * @param $value
     * @param int $options
     * @param int $depth
     * @return string
     */
    private function safe_json_encode($value, $options = 0, $depth = 512)
    {
        $encoded = json_encode($value, $options, $depth);
        if ($encoded === false && $value && json_last_error() == JSON_ERROR_UTF8) {
            $encoded = json_encode($this->utf8ize($value), $options, $depth);
        }
        return $encoded;
    }

    /**
     * @param $mixed
     * @return array|null|string|string[]
     */
    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }
        return $mixed;
    }

    /**
     * @param $d
     * @return array|string
     */
    private function utf8ize2($d)
    {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize2($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    /**
     * @param string $name
     * @param string $email
     * @return array|boolean
     */
    private function checkIfUserAlreadyExist(string $name, string $email) {
        $dao = $this->getUserDAO();
        $user = $dao->findOneByNameOrEmail($name, $email)
            ->getOneAsArray();

        return $user;
    }

    /**
     * @param $requestParams
     * @return array
     */
    private function cryptPassAndNonceFromRequestParams($requestParams)
    {
        $requestParams['pass'] = isset($requestParams['pass']) && !empty($requestParams['pass']) ?
            $requestParams['pass'] : Utils::generate_password();

        $this->password = $requestParams['pass'];

        // Force the use of weaker portable hashes. And generate the hash of password
        $t_hasher = new PasswordHash(8, TRUE);
        $requestParams['pass'] = $t_hasher->HashPassword($requestParams['pass']);

        // Get the salt
        $requestParams['nonce'] = md5(uniqid(rand(), true));
        return $requestParams;
    }

    /*
            // Must be a minimum of 8 characters
             // Must contain at least 1 number
             // Must contain at least one uppercase character
             // Must contain at least one lowercase character
             $patternPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
             preg_match($patternPassword, 'motDePasse1');
    */
//        preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, 3);


    /**
     * @param UserDTO $userObj
     * @return array
     */
    private function verificationOfRequiredFields($userObj)
    {
        $msg = [];

        $patternFrancePhone = '/^((\+|00)33\s?|0)[67](\s?\d{2}){4}$/';
        $retPhoneRegEx = preg_match($patternFrancePhone, $userObj->getPhone());

        $patternEmail = '/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/';
        $retEmailRegEx = preg_match($patternEmail, $userObj->getEmail());

        if (strlen($userObj->getName()) < 5) {
            $msg['name'] = false;
        }

        if ($userObj->getDetail() == null) {
            $msg['detail'] = false;
        }
        if ($userObj->getFirstName() == null) {
            $msg['first_name'] = false;
        }
        if ($userObj->getLastName() == null) {
            $msg['last_name'] = false;
        }
        if ($retEmailRegEx == false) {
            $msg['email'] = false;
        }
        if ($retPhoneRegEx == false) {
            $msg['phone'] = false;
        }

        if ($this->checkIfUserAlreadyExist($userObj->getName() ?? '', $userObj->getEmail()) != false) {
            $msg['name'] = false;
            $msg['message'][] = "User already exist";
        }

        // If there is at least one field that has been incorrectly completed
        if (!empty($msg)) {
            $msg['error'] = true;
            $msg['message'][] = 'Please complete all required fields correctly.';
        }

        return $msg;
    }

    /**
     * @param string $mail
     * @param string $login
     * @param string $pass
     * @param string $realname
     */
    private function sendAnEmailViaTextpattern(string $mail, string $login, string $pass, string $realname) {
        // create curl resource
        $curl = curl_init();

        // set options and url
        $opts = [
            CURLOPT_URL => "http://findeur2017.findeur.fr/senddata?user=$login&mdp=$pass&email=$mail&name=$realname",
            CURLOPT_RETURNTRANSFER => true,
        ];
        curl_setopt_array($curl, $opts);

        // $output contains the output string
        $output = curl_exec($curl);

        // close curl resource to free up system resources
        curl_close($curl);

    }

    /**
     * @param string $login
     * @param array $search
     * @return array $search
     */
    private function usernameIsNameOrEmail(string $login, array $search):array
    {
        $patternEmail = '/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/';
        $retEmailRegEx = preg_match($patternEmail, $login);
        if ($retEmailRegEx == false) {
            $search['name'] = $login;
        } else {
            $search['email'] = $login;
        }
        return $search;
    }

    /**
     * @param Request $request
     * @param string|null $name
     * @param array $roles
     * @return string
     */
    private function getToken(Request $request, string $name = null, array $roles = ["ROLE_USER"]) {
        $now = new \DateTime();
        $future = new \DateTime("+10 minutes");
        $server = $request->getServerParams();
        $jti = $this->getUniqueID();
        $payload = [
            "iat" => $now->getTimeStamp(),
            "scopes" => $roles,
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "sub" => $server["PHP_AUTH_USER"],
            "username" => $name
        ];
        $secret = getenv('SECRET_KEY_JWT');
        $token = JWT::encode($payload, $secret, "HS256");

        return $token;
    }

    /**
     * @return null|string
     */
    private function getUniqueID() {
        $ret = null;
        try{
            $ret = (new Base62)->encode(random_bytes(16));
        }catch (\Exception $exception) {
        }

        return $ret;
    }
}