<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\incblock\ViewProduct;
use onbox\block\service\sPrice;
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

     if($products){

         shuffle($products);

         foreach ($products as $product) {

             $product['price']        = sPrice::priceMarginVal($product['price_product']);
             $product['patch_url']    = HREF_PROGECT.'cart/set/'.$product['id_product'];
             $this->vars['products'] .= $this->include_tmpl('home','products',$product);
         }

     } else {

         $this->vars['products'] .= $this->include_tmpl('home','empty_products',[]);
     }

        return $this;
    }


    public function process($P) {

        $post = $P->vars['post'];

        if($post['action']) {

            switch ($post['action']) {

                case 'add_person':

                   if(filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {

                       $arr = [

                           'mail' => $post['email'],
                           'name' => $post['name']
                       ];

                       Model::Account()->set($arr);

                       self::$answer['answer'] = 'ok';

                   }

                    break;

            }
        }

        return json_encode(self::$answer);
    }


    public function numPageProduct() {

      $count = $this->countTogglePage();
      $items = '';

      for ($i = 1; $i <= $count; $i++) {

          $items .= $this->include_tmpl('incblock', 'item_num_page', [

              'num'   => $i
          ]);
      }

      return $items;
    }
}



