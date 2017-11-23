<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 01.11.17
 * Time: 21:42
 */

namespace onbox\block\vs;


class Session {

    public static  function init() {

        session_start();
    }

    public static function add($name, $val) {

        self::init();

        $_SESSION[$name] = $val;
    }

    public static function get($name) {

        self::init();

        return $_SESSION[$name];
    }

    public static function remove($name) {

        self::init();

        unset($_SESSION[$name]);
    }

    public static function remove_all() {

        self::init();

        unset($_SESSION);
    }

}