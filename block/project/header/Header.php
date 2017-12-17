<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 10:54
 */
namespace onbox\block\project\header;

use onbox\block\incblock\CartCount;
use onbox\block\incblock\Content;

class Header extends \onbox\block\vs\Template {

    use CartCount;
    use Content;

    public function block($P) {

        $this->vars['SCRIPT_URI'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER ['HTTP_HOST'];


        $count = $this->countCart();

        if($count>0) {

            $this->vars['count_cart'] = $this->include_tmpl('cart', 'count', ['count' => $count]);

        } else {

            $this->vars['count_cart'] = '';
        }

        $this->vars['content']    = $this->getContent(1);

        return $this;
    }

}