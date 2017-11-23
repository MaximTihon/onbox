<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 20.10.17
 * Time: 20:11
 */

function d($P, $pl = false) {

    if($pl) print '++++++++++++++++++++++++++++++++++++++++++++++++';
    print "<pre>";
    print_r($P);
    print "</pre>";
    if($pl) print '++++++++++++++++++++++++++++++++++++++++++++++++';
}


function dd($P) {

    print "<pre>";
    var_dump($P);
    print "</pre>";
}

include_once ('base/base.php');
include_once ('block/autoloader.php');