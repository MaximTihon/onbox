<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\service\sBrowser;
use onbox\block\vs\Cookie;
use onbox\block\vs\Model;
use onbox\block\vs\Template;

class Order extends Template {

    public function block($P) {

        if(!empty($P->vars['post']['name']) && !empty($P->vars['post']['phone'])) {

            $data = $P->vars['post'];
            $arr = [];

           foreach ($data as $k => $v) {

               if ( strcmp($k, 'product_'.$v ) == 0) {

                   $arr['product'][] = $v;

               }  elseif ( strncmp($k, 'count_', 6) == 0 ) {

                   $nk = substr($k, 6);

                   $arr['count'][$nk] = $v;

               } else {

                   $arr[$k] = urlencode($v);

               }
           }

            $id_order = Model::Order()->set([

                'data_order' => json_encode($arr),
                'date'       => time()
            ], true);

          if(!empty($id_order)) {

              Cookie::remove('cart');
              $this->vars['order'] = $this->include_tmpl('order', 'info_order', [

                  'name'     => strtoupper((urldecode($arr['name']))),
                  'id_order' => $id_order
              ]);
          }

        } else {

            sBrowser::go('cart');
        }

        return $this;
    }

}