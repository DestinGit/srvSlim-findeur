<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 23/01/2018
 * Time: 12:23
 */

namespace app\Utils;


class Utils
{
    private static $PASSWORD_SYMBOLS = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz_-!?.';

    /**
     * @param $in
     * @param $function
     * @return array|mixed
     */
    private static function doArray($in, $function)
    {
        if (is_array($in)) {
            return array_map($function, $in);
        }
        if (is_array($function)) {
            return call_user_func($function, $in);
        }
        return $function($in);
    }

    /**
     * Generates a password.
     *
     * Generates a random password of given length using the symbols set in
     * PASSWORD_SYMBOLS constant.
     *
     * Should NEVER be used as it is not cryptographically secure.
     * Will be removed in future, in lieu of sending reset request tokens.
     *
     * @param      int $length The length of the password
     * @return     string Random plain-text password
     * @example
     * echo generate_password(128);
     */
    public static function generate_password($length = 10)
    {
        static $chars;
        if (!$chars) {
            $chars = str_split(self::$PASSWORD_SYMBOLS);
        }
        $pool = false;
        $pass = '';

        for ($i = 0; $i < $length; $i++) {
            if (!$pool) {
                $pool = $chars;
            }
            $index = mt_rand(0, count($pool) - 1);
            $pass .= $pool[$index];
            unset($pool[$index]);
            $pool = array_values($pool);
        }
        return $pass;
    }

    /**
     * @param $in
     * @return array|mixed
     */
    public static function doSlash($in)
    {
        return self::doArray($in, 'safe_escape');
    }

}