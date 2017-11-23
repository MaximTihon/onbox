<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\incblock\ViewProduct;
use onbox\block\vs\Model;
use onbox\block\vs\Template;

class Home extends Template {

    use ViewProduct;

    public function block($P) {

        $arr = [
            'count' => 20,
            'limit_start' => 0
        ];


     $this->setDataPage($arr);
     $products = $this->getAllProducts($P);

     foreach ($products as $product) {

         $this->vars['products'] .= $this->include_tmpl('home','products',$product);
     }

        return $this;
    }
}
