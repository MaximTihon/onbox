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
use onbox\block\incblock\Content;

class Shipping extends Template {

    use Content;

    public function block($P) {


        $cont = $this->getContent(5);

        if($cont) {

            $this->vars['html'] = html_entity_decode($cont);

        } else {

            $this->vars['html'] = 'Извините! Данные заполняются';

        }

        return $this;
    }
}