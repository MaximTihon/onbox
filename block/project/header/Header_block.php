<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 10:54
 */
namespace onbox\block\project\header;

use onbox\block\incblock\CartCount;

class Header_block extends \onbox\block\vs\Template {

    use CartCount;

    public function block() {

        $this->vars['scr_uri'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER ['HTTP_HOST'];

        $count = $this->countCart();

        if($count > 0) {

            $tmpl_count = $this->include_tmpl('cart', 'count', ['count' => $count]);

        } else {

            $tmpl_count = '';
        }

        $this->vars['count_cart'] = $this->include_tmpl('cart', 'cart', ['count' => $tmpl_count, 'scr_uri' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER ['HTTP_HOST']]);


        return $this;
    }
}