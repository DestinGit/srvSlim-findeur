<?php
/**
 * Created by IntelliJ IDEA.
 * User: yemei
 * Date: 06/02/2018
 * Time: 14:15
 */

namespace app\Middlewares;


use Firebase\JWT\JWT;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class authMiddleware
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, Callable $next)
    {
        // TODO: Implement __invoke() method.
        $headers = getallheaders();
        // getServerParams();
        $headersFromSlim = $request->getHeader('Authorization');

        $jwt = $request->getHeaders();
        echo $jwt['HTTP_AUTHORIZATION'][0];
        //$token = JWT::decode(['HTTP_AUTHORIZATION'][0], getenv('SECRET_KEY_JWT'), array('HS256'));
//echo '<br>';
//var_dump($token);
die();
        // return $next($request, $response);
    }


/*    private function getToken() {
        $now = new \DateTime();
        $future = new \DateTime("now +2 hours");
        try {
            $jti = (new \Tuupola\Base62)->encode(random_bytes(16));
        } catch (\Exception $e) {
        }

        $secret = getenv('SECRET_KEY_JWT');

        $payload = [
            "jti" => $jti,
            "iat" => $now->getTimeStamp(),
            "nbf" => $future->getTimeStamp()
        ];

        $token = JWT::encode($payload, $secret, "HS256");

        return $token;
    }*/

}