<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27.11.17
 * Time: 23:02
 */

namespace onbox\block\incblock;

use onbox\block\vs\Cookie;

trait CartCount
{
    public function countCart() {

        $json_order = Cookie::get('cart');

        if(isset($json_order)) {

            $order = json_decode($json_order, true);

            $count = count($order);

            return $count;
        }
    }
}