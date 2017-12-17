<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\vs\Template;
use onbox\block\incblock\ViewProduct;
use onbox\block\service\sPrice;

class Showcase extends Template {

    use ViewProduct;

    public function block($P) {


        $this->count = 20;


        if($P->action == 'p') {


            $num = $P->vars['data'];

            $this->limit_start = $this->changeNumPage($num);;

        } else {

            $this->limit_start = 0;
        }


        $products = $this->getAllProducts($P);

        if(!empty($products)) {

            foreach ($products as $product) {

                $product['price']        = sPrice::priceMarginVal($product['price_product']);
                $product['patch_url'] = HREF_PROGECT.'cart/set/'.$product['id_product'];
                $this->vars['products'] .= $this->include_tmpl('home','products',$product);
            }
        } else {

            $this->vars['products'] = 'Данных еще нет';
        }

        $this->vars['num'] =  $this->numPageProduct();


        return $this;
    }

    public function numPageProduct() {

        $count = $this->countTogglePage();
        $items = '';

        if($count > 1) {

            for ($i = 1; $i <= $count; $i++) {

                $items .= $this->include_tmpl('incblock', 'item_num_page', [

                    'num'   => $i
                ]);
            }
        }

        return $items;
    }

    private function changeNumPage($numPage) {

            $page = $this->count * ($numPage - 1);

        return $page;
    }

}

