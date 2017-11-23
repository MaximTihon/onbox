<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 30.10.17
 * Time: 21:08
 */

namespace onbox\block\service;


class sBrowser {

    public static function go($relative_url) {

    header("Location:".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/'.$relative_url);

    exit;
    }

}