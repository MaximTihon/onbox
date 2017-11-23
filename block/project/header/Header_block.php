<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 10:54
 */
namespace onbox\block\project\header;

class Header_block extends \onbox\block\vs\Template {

    public function block() {

        $this->vars['SCRIPT_URI'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER ['HTTP_HOST'];

        return $this;
    }
}