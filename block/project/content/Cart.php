<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\service\sPrice;
use onbox\block\vs\Cookie;
use onbox\block\vs\Model;
use onbox\block\vs\Template;

class Cart extends Template {

    public function block($P) {

        $item_order = '';

        $json_id_order = Cookie::get('cart');

        $id_orders = json_decode($json_id_order, true);

      if(!empty($id_orders)) {

          foreach ($id_orders as $key => $id) {

              $products = Model::query('SELECT * FROM product pr INNER JOIN price USING (id_product) INNER JOIN img_product USING (id_product) WHERE id_product='.$id);

              $product = $products->fetchAll(\PDO::FETCH_ASSOC);

              $item_order .= $this->include_tmpl('cart', 'item_order', array_merge([

                  'patch_url_cancel' => HREF_PROGECT.'cart/cancel/'.$key,
                  'price'            => sPrice::priceMarginVal($product[0]['price_product'])

              ], $product[0]));
          }

          $this->vars['order'] = $this->include_tmpl('cart', 'order', [

              'patch'      => HREF_PROGECT,
              'item_order' => $item_order
          ]);

      } else {

          $this->vars['order'] = $this->include_tmpl('cart', 'empty_order', []); //вывести что нет в корзине товара
      }

      return $this;
    }

    public function process($P) {

        if(isset($P->action)) {

            switch ($P->action) {

                case 'set':

                    $data_order = array();

                    $id = $P->vars['data'];

                    $order = Cookie::get('cart');

                    if($order) {

                        $data_order = json_decode($order, true);


                        if (!in_array($id, $data_order)) {

                            array_push($data_order, $id);

                        }

                    } else {

                        $data_order[] = $id;
                    }

                    $json_order = json_encode($data_order);


                    if(!empty($json_order)) {

                        Cookie::add('cart', $json_order, 3600*60);
                    }

                    self::$answer['message'] = $this->include_tmpl('cart', 'modal_dialog',[

                        'url' => HREF_PROGECT.'cart/'
                    ]);

                    break;

                case 'cancel':

                    $key = $P->vars['data'];

                    $order = Cookie::get('cart');

                    $data_order = json_decode($order, true);

                    unset($data_order[$key]);

                    if(!empty($data_order)) {

                        $json_data_order = json_encode($data_order);

                        Cookie::add('cart', $json_data_order, 3600*60);

                    } else {

                        Cookie::add('cart', '', -3600);
                    }

                    break;
            }

        }

        return json_encode(self::$answer);
    }

}