<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 23.11.17
 * Time: 16:20
 */

namespace onbox\block\incblock;
use onbox\block\vs\Model;
use onbox\block\vs\Template;

trait ViewProduct
{
    public $count;
    public $limit_start;

    private function setDataPage($data_page = array()) {

        $this->count       = $data_page['count'];
        $this->limit_start = $data_page['limit_start'];
    }

    public function getAllProducts($P) {

      switch ($P->action) {

          case 'category':

              $id_category = $P->vars['data'];

              $products = Model::query('SELECT * FROM product pr 
                                              INNER JOIN price USING (id_product) 
                                              INNER JOIN img_product USING (id_product) 
                                              WHERE id_category='.$id_category.' and status=1
                                              LIMIT '.$this->limit_start.' ,'.$this->count);

              break;


          case 'brand':

              $id_brand = $P->vars['data'];

              $products = Model::query('SELECT * FROM product pr 
                                              INNER JOIN price USING (id_product) 
                                              INNER JOIN img_product USING (id_product) 
                                              WHERE id_brand='.$id_brand.' and status=1 
                                              LIMIT '.$this->limit_start.' ,'.$this->count);

              break;

          default:

              $products = Model::query('SELECT * FROM product pr INNER JOIN price USING (id_product) INNER JOIN img_product USING (id_product) WHERE status=1 ORDER BY id_product LIMIT '.$this->limit_start.' ,'.$this->count);

              break;

      }

        $row = $products->fetchAll(\PDO::FETCH_ASSOC);

        return $row;
    }


    public function countTogglePage() {

      $q = Model::query('SELECT COUNT(*) FROM product');

      $count_product = $q->fetchAll(\PDO::FETCH_ASSOC);

      $count = $count_product[0]['COUNT(*)'];


      if($this->count) {

          $num = $count / $this->count;

      } else {

          $num = $count / 1;
      }

      return ceil($num);
    }
}