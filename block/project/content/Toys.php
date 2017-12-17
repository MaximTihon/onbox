<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\vs\Template;
use onbox\block\vs\Model;

class Toys extends Template {


    public function block($P) {


    }

    public function process($P) {

        switch ($P->action) {

            case 'get_toys':

                $id_brand = $P->vars['data'];

                $brands = Model::Brand()->get('id_category='.$id_brand);

                if(!empty($brands)) {

                    self::$answer['answer'] = $brands;

                }

                break;
        }

        return json_encode(self::$answer);
    }
}