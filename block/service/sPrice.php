<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 30.10.17
 * Time: 21:08
 */

namespace onbox\block\service;


class sPrice {

    private static $margin = 25;

    public static function priceMarginVal ($price) {

        $price_product = $price / 100 * self::$margin;

        return $price + $price_product;
    }

}
