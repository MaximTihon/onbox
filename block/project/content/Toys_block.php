<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 10:54
 */
namespace onbox\block\project\content;

use onbox\block\vs\Model;

class Toys_block extends \onbox\block\vs\Template {

    public function block() {


        $toys = Model::Category()->get();

        if(!empty($toys)) {

            foreach ($toys as $toy) {

                $this->vars['toys_block'] .= $this->include_tmpl('toys_block', 'item_toy', $toy);
            }

        } else {


            $this->vars['toys_block'] = $this->include_tmpl('toys_block', 'empty_item_toy', []);
        }

        $brand = Model::Brand()->get();

        if(count($brand) >5) {

            $arr_brand = array_rand($brand, 5);

        } else {

            $arr_brand = $brand;
        }

        foreach ( $arr_brand as $br) {

            $this->vars['brand_block'] .= $this->include_tmpl('toys_block', 'brand_block', $br);
        }

        return $this;
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