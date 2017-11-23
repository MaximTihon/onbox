<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 23.11.17
 * Time: 16:20
 */

namespace onbox\block\incblock;
use onbox\block\vs\Model;

trait ViewProduct
{
    public $count;
    public $limit_start;

    private function setDataPage($data_page = array()) {

        $this->count       = $data_page['count'];
        $this->limit_start = $data_page['limit_start'];
    }

    public function getAllProducts($P) {

        $products = Model::query('SELECT * FROM product pr INNER JOIN price USING (id_product) INNER JOIN img_product USING (id_product) ORDER BY id_product LIMIT '.$this->limit_start.' ,'.$this->count);

        $row = $products->fetchAll(\PDO::FETCH_ASSOC);

        return $row;
    }
}