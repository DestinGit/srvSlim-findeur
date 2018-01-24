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
use Psr\{
    Container\ContainerExceptionInterface, Container\ContainerInterface, Container\NotFoundExceptionInterface
};
use Slim\Http\Request;
use Slim\Http\Response;


class UserCtrl
{
    private $ctx;

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


    public function addUserPost(Request $request, Response $response)
    {
        // Retrieving query parameters and encrypting the password and generating the associated salt
        $requestParams = $request->getParams();
        $requestParams = $this->cryptPassAndNonceFromRequestParams($requestParams);

        // Give a default privilege for the user
        $requestParams['privs'] = 5;

        // Hydrate the object that represents the user
        $userObj = $this->getUserDTO();
        $userObj->hydrate($requestParams);

        // Verification of required fields
        $msg = $this->verificationOfRequiredFields($userObj);

        // If there is no error message, persiste data on DB
        if (empty($msg)) {
            $this->getUserDAO()->save($userObj)->flush();
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
     * @param $requestParams
     * @return array
     */
    private function cryptPassAndNonceFromRequestParams($requestParams)
    {
        $requestParams['pass'] = isset($requestParams['pass']) && !empty($requestParams['pass']) ?
            $requestParams['pass'] : Utils::generate_password();

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

        if ($userObj->getDetail() == null) {
            $msg['detail'] = false;
        }
        if ($userObj->getFirstName() == null) {
            $msg['first_name'] = false;
        }
        if ($userObj->getLastName() == null) {
            $msg['last_name'] = false;
        }
        if ($userObj->getEmail() == null || $retEmailRegEx === false) {
            $msg['email'] = false;
        }
        if ($userObj->getPhone() == null || $retPhoneRegEx === false) {
            $msg['phone'] = false;
        }

        return $msg;
    }
}