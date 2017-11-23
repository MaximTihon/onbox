<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 21:37
 */

namespace onbox\block\project\content;

use onbox\block\service\sBrowser;
use onbox\block\service\sFile;
use onbox\block\service\sInitBase;
use onbox\block\vs\Model;
use onbox\block\vs\Template;

class Datm extends Template {

    public $table;

    public function block($P) {

//       $datm = Model::Datm()->sessionData();

       if(empty($datm)) { //!

           switch ($P->action) {

               case 'brand':
               case 'product':
               case 'category':

                   $items = '';

                   $table = ucwords($P->action);

                   $brands = Model::$table()->get();

                   if(!empty($brands)) {

                       foreach($brands as $item) {

                           $items .= $this->include_tmpl('datm/inc', $P->action.'_item', $item);
                       }
                   }

                   if(empty($items)) {

                       $items = "Вы еще не добавили Брэнды";
                   }

                   $this->vars['html'] = $this->include_tmpl('datm', $P->action, [

                       'items'  => $items
                   ]);

                   break;

               default:

                   $this->vars['html'] = 'Статистика и информация о продажах';

                   break;
           }

       } else {

           sBrowser::go('reg');
       }

       return $this;
    }


    public function process($P) {

//        $datm = Model::Datm()->sessionData();

        if(empty($datm)) {

            switch ($P->action) {

                case 'add_form_category':
                case 'add_form_brand':
                case 'add_form_product':

                    if($P->action == 'add_form_brand') {

                        $category = Model::Category()->get();
                        $option = '';

                        foreach ($category as $item) {

                            $option .= $this->include_tmpl('datm/option', 'category_option', $item);
                        }

                        $arr = ['option' => $option];

                    } elseif ($P->action == 'add_form_product') {

                        $category = Model::Category()->get();
                        $option_category = '';
                        $brand = Model::Brand()->get();
                        $option_brand = '';

                        foreach ($category as $item) {

                            $option_category .= $this->include_tmpl('datm/option', 'category_option', $item);
                        }

                        foreach ($brand as $item) {

                            $option_brand .= $this->include_tmpl('datm/option', 'brand_option', $item);
                        }

                        $arr = [
                            'option_category' => $option_category,
                            'option_brand'    => $option_brand,
                        ];

                    }

                    self::$answer['html'] = $this->include_tmpl('datm/form', $P->action, $arr) ;

                    break;

                case 'edit_form_category':
                case 'edit_form_brand':

                    $id = $P->vars['post']['id'];

                    $table_name = substr($P->action, 10);
                    $table = ucfirst($table_name);

                    $arr = Model::$table()->get('id_'.$table_name.'='.$id);

                    if($P->action == 'edit_form_brand') {

                        $category = Model::Category()->get();
                        $option = '';

                        foreach ($category as $item) {

                            $option .= $this->include_tmpl('datm/option', 'category_option', $item);
                        }

                        $arr[0]['option'] =$option;
                    }

                    self::$answer['html'] = $this->include_tmpl('datm/form', $P->action, $arr[0]) ;

                    break;

                case 'edit_form_product':

                    $id           = $P->vars['post']['id'];
                    $product      = Model::Product()->get('id_product='.$id);
                    $category     = Model::Category()->get();
                    $option_category = '';
                    $brand        = Model::Brand()->get();
                    $option_brand = '';
                    $img          = Model::Img_product()->get('id_product='.$id);
                    $price        = Model::Price()->get('id_product='.$id);
                    array_shift($img[0]);
                    array_shift($price[0]);

                    foreach ($category as $item) {

                        $option_category .= $this->include_tmpl('datm/option', 'category_option', $item);
                    }

                    foreach ($brand as $item) {

                        $option_brand .= $this->include_tmpl('datm/option', 'brand_option', $item);
                    }

                    $arr = [
                        'option_category' => $option_category,
                        'option_brand'    => $option_brand,
                    ];

                    self::$answer['html'] = $this->include_tmpl('datm/form', $P->action,array_merge($arr,$product[0],$img[0],$price[0])) ;

                    break;

                case 'category':

                    switch ($P->vars['post']['action']) {

                        case 'unset_category':

                            $id = $P->vars['post']['id'];

                            Model::Category()->del('id_category='.$id);

                            break;

                        case 'add_category':

                            $arr = [
                                'name_category' => $P->vars['post']['name_category'] ,
                                'description_category' => $P->vars['post']['description_category'],
                                'active' => $P->vars['post']['active']
                            ];

                            Model::Category()->set($arr);

                            break;

                        case 'edit_category':

                            $id = $P->vars['post']['id_category'];

                            $arr = [
                                'id_category'          => $P->vars['post']['id_category'],
                                'name_category'        => $P->vars['post']['name_category'],
                                'description_category' => $P->vars['post']['description_category'],
                                'active'               => $P->vars['post']['active']
                            ];

                            Model::Category()->updata($arr, 'id_category='.$id);

                            break;
                    }

                    break;

                case 'brand':


                    switch ($P->vars['post']['action']) {

                        case 'unset_brand':

                            $id = $P->vars['post']['id'];

                            Model::Brand()->del('id_brand='.$id);

                            break;

                        case 'add_brand':

                            $arr = [
                                'id_category'       => $P->vars['post']['id_category'] ,
                                'name_brand'        => $P->vars['post']['name_brand'] ,
                                'description_brand' => $P->vars['post']['description_brand'],
                                'active'            => $P->vars['post']['active']
                            ];

                            $dir = PATCH_LOAD.'file/brand/';

                            $name_file = sFile::copyFile($_FILES, $dir);

                            $arr['img_brand'] = $name_file;

                            Model::Brand()->set($arr);

                            break;

                        case 'edit_brand':

                            $id = $P->vars['post']['id_category'];

                            $arr = [
                                'id_category'          => $P->vars['post']['id_category'],
                                'name_category'        => $P->vars['post']['name_category'],
                                'description_category' => $P->vars['post']['description_category'],
                                'active'               => $P->vars['post']['active']
                            ];

                            Model::Category()->updata($arr, 'id_category='.$id);

                            break;

                    }

                    break;

                case 'product':

                    switch ($P->vars['post']['action']) {

                        case 'add_product':

                            $id_product = $this->addProduct($P);

                            if(!empty($id_product)){

                                $this->addPrice($id_product,$P);
                                $this->addImgProduct($id_product , $P);
                            }

                            break;

                        case 'unset_product':

                            $id = $P->vars['post']['id'];
                            $arr = Model::Product()->get('id_product='.$id);
                            $dir = PATCH_LOAD.'file/product/'.$arr[0]['id_brand'].'/'.$id;

                            $op = opendir($dir);

                                while (false !== ($entry = readdir($op))) {

                                    if($entry == '.' || $entry == '..') {

                                        continue;

                                    } else {

                                        if (file_exists($dir . '/' . $entry)) {

                                            unlink($dir . '/' . $entry);
                                        }
                                    }

                            }

                            closedir($op);

                            rmdir($dir);

                                Model::Product()->del('id_product='.$id);
                                Model::Img_product()->del('id_product='.$id);
                                Model::Price()->del('id_product='.$id);

                            break;

                        case 'edit_product':

                            $id = $P->vars['post']['id_category'];

                            $arr = [
                                'id_category'           => $P->vars['post']['id_category'],
                                'id_brand'              => $P->vars['post']['id_brand'],
                                'name_product'          => $P->vars['post']['name_product'],
                                'description_product'   => $P->vars['post']['description_product'],
                                'status'                => 0, //$P->vars['post']['description_category'],
                                'article'               => $P->vars['post']['article']
                            ];

                            Model::Product()->updata($arr, 'id_category='.$id);

                            break;

                        case 'edit_file_product':

                            d($P);

                            break;

                        case 'edit_price_product':

                            d($P);

                            break;

                    }

                    break;

            }

        } else {

            sBrowser::go('reg');
        }

        return json_encode(self::$answer);
    }


    private function addProduct($P) {

        $arr = [
            'id_category'         => $P->vars['post']['id_category'],
            'id_brand'            => $P->vars['post']['id_brand'],
            'name_product'        => $P->vars['post']['name_product'],
            'description_product' => $P->vars['post']['description_product'],
            'status'              => 0, //$P->vars['post']['status'],
            'article'             => $P->vars['post']['article'],
        ];

       $id_product = Model::Product()->set($arr, true);

       return $id_product;
    }

    private function addPrice($id_product, $P) {

        $arr = [
            'id_product'        => $id_product,
            'price_product'     => $P->vars['post']['price'],
            'sale_product'      => json_encode($P->vars['post']['name_product'])
            ];

        Model::Price()->set($arr);
    }

    private function addImgProduct($id_product, $P) {

        $id_brand    = $P->vars['post']['id_brand'];

        $dir = PATCH_LOAD.'file/product/'.$id_brand;

        if(is_dir($dir) == false) {

            if (!mkdir($dir, 0777, true)) {
                die('Не удалось создать директории...');
            }

        }

        $patch_file = $dir.'/'.$id_product;

        if(is_dir($patch_file) == false) {

            if (!mkdir($patch_file, 0777, true)) {
                die('Не удалось создать директории...');
            }

        }

         $data_file_name = sFile::copyFileAll($_FILES, $patch_file);

        $data_file_name['id_product'] = $id_product;

         Model::Img_product()->set($data_file_name);
    }



    private function getNameData ($id, $tablet) {

        $table = ucfirst($tablet);

        $data = Model::$table()->get('id_'.$tablet.'='.$id);

       return $data;
    }
}