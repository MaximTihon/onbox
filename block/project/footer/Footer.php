<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 10:55
 */
namespace onbox\block\project\footer;

class Footer extends \onbox\block\vs\Template {

    public function block($P) {

        $this->vars['k'] = '15';

        return $this;
    }
}