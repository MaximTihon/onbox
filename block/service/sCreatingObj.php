<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 17.07.17
 * Time: 22:46
 */

namespace onbox\block\service;


class sCreatingObj {

    public static $instance = null;


    public static function getInstance($active, $block = null) {


            switch ($block) {

                case 'header':

                    self::$instance = 'onbox\block\project\header\\' . ucwords($active);

                    break;


                case 'footer':

                    self::$instance = 'onbox\block\project\footer\\' . ucwords($active);

                    break;

                default:

                    if ($active) {

                        self::$instance = 'onbox\block\project\content\\' . ucwords($active);

                    } else {

                        self::$instance = 'onbox\block\vs\Template';
                    }

                    break;

            }

            self::$instance = new self::$instance();

        return self::$instance;
    }

    protected function __construct() {

    }

    private function __clone() {

    }
}