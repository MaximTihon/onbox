<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 01.11.17
 * Time: 21:43
 */

namespace onbox\block\vs;


class Cookie {

    public static function add($name, $val, $time = null) {

        if($time == null) {

            setcookie($name, $val, time()+60*60*24*30, '/'); //30 d

        } else {

            setcookie($name, $val, time()+ $time, '/');
        }
    }


    public static function get($name) {

        return $_COOKIE[$name];
    }

    public static function remove($name) {

        setcookie($name, '', time() - 3600);
    }
}