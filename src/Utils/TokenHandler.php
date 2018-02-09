<?php
/**
 * Created by IntelliJ IDEA.
 * User: yemei
 * Date: 07/02/2018
 * Time: 14:39
 */

namespace app\Utils;


use Firebase\JWT\JWT;
use Slim\Http\Request;
use Tuupola\Base62;

class TokenHandler
{
    private static function getToken(Request $request, string $name = null) {
        $now = new \DateTime();
        $future = new \DateTime("+10 minutes");
        $server = $request->getServerParams();
        $jti = TokenHandler::getUniqueID();
        $payload = [
            "iat" => $now->getTimeStamp(),
            "scopes" => ["ROLE_USER"],
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
    private static function getUniqueID() {
        $ret = null;
        try{
            $ret = (new Base62)->encode(random_bytes(16));
        }catch (\Exception $exception) {
        }

        return $ret;
    }

}