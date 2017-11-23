<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 29.10.17
 * Time: 8:33
 */

namespace onbox\block\model;


use onbox\block\vs\Cookie;
use onbox\block\vs\Model;
use onbox\block\vs\Session;

class Datm extends Model {

    public $primare_key = 'datm_sk';


    public function sessionData() {

        $cookie_key = Cookie::get($this->primare_key);
        $datm_sk = Session::get($cookie_key);

        if($datm_sk) {

           $datm = Model::Datm()->get('datm_sk=\''.$datm_sk.'\'');
        }

        return $datm;
    }




}