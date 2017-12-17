<?php

namespace onbox\block\vs;

class InitData {

    public $object;
    public $action;
    public $id;
    public $vars = array();


    public function __construct() {

        $this->parseUrl();
        $this->parseGetData();

        return $this;
    }


    private function parseUrl() {

        $Url = array();

        $url = explode('/', $_SERVER['SCRIPT_URL'],4);

        array_shift($url);

       if(count($url) == 3) {

           $this->object = htmlentities(trim($url[0]));
           $this->action = htmlentities(trim($url[1]));
           $this->vars['data']   = htmlentities(trim($url[2]));

       } else if(count($url) == 2) {

           $this->object     = htmlentities(trim($url[0]));
           $this->action     = htmlentities(trim($url[1]));

       } else {

           $this->object = htmlentities(trim($url[0]));
       };

       return $Url;
    }

    private function parseGetData() {

        if($_GET) {

            $this->vars['get'] = $this->chekData($_GET);
        }

        if($_POST) {

            $this->vars['post'] = $this->chekData($_POST);
        }

        return $this;
    }

    private function chekData($ar) {

        $arr = [];

        foreach ($ar as $key => $val) {

            $arr[htmlentities(trim($key))] = htmlentities(trim($val));

        }

        return $arr;
    }
}