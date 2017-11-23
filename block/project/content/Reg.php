<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\service\sBrowser;
use onbox\block\service\sPassword;
use onbox\block\service\sValidate;
use onbox\block\vs\Cookie;
use onbox\block\vs\Model;
use onbox\block\vs\Session;
use onbox\block\vs\Template;

class Reg extends Template {

    public  $action = 'enter';

    public function block($P) {

        $this->vars['action'] = $_SERVER['REDIRECT_SCRIPT_URI'].'/'.$this->action;

        return $this;
    }

    public function process($P) {


        if($P->action){

            switch ($P->action) {

                case 'enter':

                    $logo = $P->vars['post']['logo'];
                    $pass = $P->vars['post']['pass'];

                    if(!sValidate::$code){

                        $datm = Model::Datm()->get('logo=\''.$logo.'\'');

                        if(!empty($datm)) {

                            if(sPassword::comparePasswords($pass, $datm[0]['pass'])) {

                                $model = Model::Datm()->getModel();

                                $cookie_key = md5(substr( $datm[0]['pass'], 0,10));
                                $session_data = md5(sPassword::createPassword($pass));

                                $result = Model::Datm()->updata(['datm_sk' => $session_data], 'id_datm='.$datm[0]['id_datm']);

                                if($result) {

                                    Cookie::add($model['primare_key'], $cookie_key);
                                    Session::add($cookie_key, $session_data);

                                    sBrowser::go('datm');

                                }

                            } else {

                                //возрощать на форму с выводом ошибки не соответствия пароля

                                die('no pass');
                            }

                        } else {

                            //выводить что такого пользователя нету

                            die('no user');
                        }
                    }


                    break;
            }
        }

        return $this;
    }

}