<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\incblock\Content;
use onbox\block\vs\Model;
use onbox\block\vs\Template;

class Guarantee extends Template {

    use Content;

    public function block($P) {


        $cont = $this->getContent(6);

        if($cont) {

            $this->vars['html'] = html_entity_decode($cont);

        } else {

            $this->vars['html'] = 'Извините! Данные заполняются';

        }

        return $this;
    }
}