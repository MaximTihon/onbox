<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 21.10.17
 * Time: 17:30
 */
namespace onbox\block\service;

class sInitBase {

    public static function incBaseDoc($name) {

        $file = $_SERVER['DOCUMENT_ROOT'].'base/'.$name.'.php';

        if(file_exists($file)) {

            return include $file;
        }
    }

    public static function parseDataDB() {

        $file = $_SERVER['DOCUMENT_ROOT'].'base/db.ini';

        return parse_ini_file($file);
    }
}