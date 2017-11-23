<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 01.11.17
 * Time: 22:34
 */

namespace onbox\block\service;


class sPassword {

    public static function comparePasswords($pass, $hash) {

        if(password_verify($pass, $hash)) {

            return true;

        } else {

            return false;
        }
    }

    public static function createPassword($pass) {

        $options = [
            'cost' => 12
        ];

        $hash = password_hash($pass, PASSWORD_DEFAULT);

        return $hash;
    }



}