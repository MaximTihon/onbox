<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 19:12
 */

namespace onbox\block\service;


class sParseTmpl {

    public static $html;
    public static $vars = array();

    public static function parseTmpl($content,  $obj, $P = null) {

        if (method_exists($obj, 'block')) {

            self::$vars = $obj->block($P)->vars;
        }

        if($P->vars['post']['proc'] === 'process' || $P->action == 'enter') {

            if (method_exists($obj, 'process')) {

                return $obj->process($P);
            }

        }

        if(self::$vars) {

            preg_match_all('/{[a-z_\.\/]+\}/', $content, $v);

            $html = str_replace($v[0], self::$vars, $content);

            self::$html = $html;

        } else {

            self::$html = $content;
        }

        return self::$html;
    }
}


//foreach ($v[0] as $val) {
//
//    $key = trim($val,'{}');
//
//    $html = str_replace($val, self::$vars[$key], $content);
//}