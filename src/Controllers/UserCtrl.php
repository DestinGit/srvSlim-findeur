<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 16:17
 */

namespace app\Controller;

use app\DAO\UserDAO;
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
    public function getAllUsers(Request $request, Response $response) {
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
    public function getOneUser(Request $request, Response $response, array $args) {
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
    public function findUser(Request $request, Response $response, array $args) {

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
    public function findUserPost(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        //$name = filter_var('user', FILTER_SANITIZE_STRING);
        //$pass = filter_var('mdp', FILTER_SANITIZE_STRING);

        // create curl resource
        $curl = curl_init();
/*        $name = $request->getParam("user");
        $pass = $request->getParam('mdp');
        $pass = $args['mdp'] ?? null;*/

        $paramsTab = [
            'user' => $request->getParam("user") ?? null,
            'mdp' => $request->getParam('mdp') ?? null
        ];
        $params = [
            'user' => 'gando',
            'mdp' => 'sd5fp68nhg32'
        ];
//        $params_string = http_build_query($params);
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

/*        $response->withHeader('Access-Control-Allow-Origin', '')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
*/
        return $response->withJson($arr);
    }


    /**
     * @return UserDAO
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getUserDAO() {
        return $this->ctx->get('user.dao');
    }

    /**
     * @param $myString
     * @param $findMe
     * @return mixed
     */
    private function returnArrayFromPartOfString($myString, $findMe) {
        $pos = strpos($myString, $findMe);
        $res = substr($myString, 0, $pos);
        $arr = json_decode($res, true);
        return $arr;
    }

    /**
     * @param $value
     * @param int $options
     * @param int $depth
     * @return string
     */
    private function safe_json_encode($value, $options = 0, $depth = 512) {
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
    private function utf8ize($mixed) {
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
    private function utf8ize2($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize2($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }
}