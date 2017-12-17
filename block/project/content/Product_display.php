<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\vs\Model;
use onbox\block\vs\Template;
use onbox\block\service\sPrice;

class Product_display extends Template {

    public function block($P) {

        $id_product = $P->action;

        $products = Model::query('SELECT * FROM product pr INNER JOIN price USING (id_product) INNER JOIN img_product USING (id_product) WHERE id_product='.$id_product);

        $product = $products->fetchAll(\PDO::FETCH_ASSOC);

        $arr['patch_url'] = HREF_PROGECT.'cart/set/'.$id_product;
        $arr['img_block'] = $this->getImgProduct($id_product, $product[0]['id_brand']);
        $arr['price']        = sPrice::priceMarginVal($product[0]['price_product']);
        $this->vars['item'] = $this->include_tmpl('product_display', 'item_product',array_merge($product[0], $arr));

        return $this;
    }

    public function process($P) {



        return json_encode(self::$answer);
    }

    private function getImgProduct($id_product, $id_brand) {

        $data_img = Model::Img_product()->get('id_product='.$id_product);

        $img_block = '';

        array_shift($data_img[0]);

        foreach ($data_img[0] as $img) {

            if (!empty($img)) {

                $img_block .= $this->include_tmpl('product_display', 'img_block', [

                    'url_img'  => '/file/product/'.$id_brand.'/'.$id_product.'/'.$img.'',
                    'name_img' => $img,
                ]);
            }
        }

        return $img_block;

    }

}