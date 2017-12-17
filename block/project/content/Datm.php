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
use onbox\block\incblock\ViewProduct;

class Datm extends Template {

    use ViewProduct;

    public $table;

    public function block($P) {

       $datm = Model::Datm()->sessionData();

       if(!empty($datm)) { //!

           $this->vars['url_datms']     = HREF_PROGECT.'datm/';
           $this->vars['url_product']  = HREF_PROGECT.'datm/product/';
           $this->vars['url_category'] = HREF_PROGECT.'datm/category/'; //http://onbox.dev/datm/category
           $this->vars['url_brand']    = HREF_PROGECT.'datm/brand/'; //http://onbox.dev/datm/brand/
           $this->vars['url_static']   = HREF_PROGECT.'datm/static/';
           $this->vars['url_content']  = HREF_PROGECT.'datm/content/';

           switch ($P->action) {

               case 'content':

                   $content      = Model::Content()->get();
                   $item_content = '';
                   $cont = '';
                   $id_content = '';

                   if($P->vars['get']['page']) {

                       $id = $P->vars['get']['page'];
                       $id_content = $id;
                       $arr_cont = Model::Content()->get('id_content='.$id);
                       $cont = urldecode($arr_cont[0]['content']);
                   }

                   foreach ($content as $c) {

                       $item_content .= $this->include_tmpl('datm/inc', 'item_content', $c);
                   }

                   $this->vars['html'] = $this->include_tmpl('datm', $P->action, [

                       'option'      => $item_content,
                       'cont'        => $cont,
                       'id_content'  => $id_content
                   ]);

                   break;

               case 'brand':
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

               case 'product':

                   $items = '';
                   $this->count = 20;

                   $p = explode('/', $P->vars['data']);

                   if($p[0] == 'p') {

                       $num = $p[1];

                       $this->limit_start = $this->changeNumPage($num);;

                   } else {

                       $this->limit_start = 0;
                   }


                   $products = Model::query('SELECT * FROM product pr INNER JOIN price USING (id_product) INNER JOIN img_product USING (id_product) ORDER BY id_product LIMIT '.$this->limit_start.' ,'.$this->count);

                   $row = $products->fetchAll(\PDO::FETCH_ASSOC);

                   if(!empty($products)) {

                       foreach ($row as $product) {

                           $items .= $this->include_tmpl('datm/inc',$P->action.'_item',$product);
                       }
                   } else {

                       $items = 'Данных еще нет';
                   }

                   $this->vars['html'] = $this->include_tmpl('datm', $P->action, [

                       'items'  => $items,
                       'num'    => $this->numPageProduct()
                   ]);


                   break;

               default:

//                   $orders = Model::Order()->get();

                       $q = Model::query('SELECT * FROM `order` WHERE status_order=1');
                       $orders = $q->fetchAll();

                   if(!empty($orders)) {

                       $items = '';
                       $arr = [];

                       foreach ($orders as $ord) {



                           $data_order = json_decode($ord['data_order'], true);

                           $product = $this->getInfoProduct($data_order);

                           $arr = [

                               'id_order' => $ord['id_order'],
                               'time'     => date('d:M G:i ', $ord['date']),
                               'status'   => $ord['status_order'],
                               'address'  => urldecode($data_order['address']),
                               'name'     => urldecode($data_order['name']),
                               'phone'    => urldecode($data_order['phone']),
                               'sum'      => $data_order['sum'],
                               'product'  => $product

                           ];

                           $items .= $this->include_tmpl('datm/order', 'item_order',$arr);
                       }

                       $this->vars['html'] = $this->include_tmpl('datm', 'order', [

                           'items' => $items
                       ]);

                   } else {

                       $this->vars['html'] = 'Пока нет заказов';
                   }

                   break;
           }

       } else {

           sBrowser::go('reg');
       }

       return $this;
    }


    public function process($P) {

        $datm = Model::Datm()->sessionData();

        if(!empty($datm)) {

            switch ($P->action) {

                case 'add_content':

                   $id   = $P->vars['post']['id_content'];
                   $cont = $P->vars['post']['content'];

                   if(!empty($id)) {

                       Model::Content()->updata(['content' => htmlentities(urlencode($cont))], 'id_content='.$id);
                   }

                    break;

                case 'processed_order':

                 $id = $P->vars['post']['id'];
                 Model::Order()->updata(['status_order' => 0], 'id_order='.$id);

                    break;

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

                    $data = $this->parseDataProduct($product[0]['data_product']);

                    $arr = [
                        'option_category' => $option_category,
                        'option_brand'    => $option_brand,
                        'category'        => $this->getNameData($product[0]['id_category'], 'category'),
                        'brand'           => $this->getNameData($product[0]['id_brand'], 'brand'),
                        'woman_c'           => $data['woman'] ? 'checked' :  '' ,
                        'man_c'             => $data['man']   ? 'checked' :  ''
                    ];


                    self::$answer['html'] = $this->include_tmpl('datm/form', $P->action, array_merge($arr,$data,$product[0],$img[0],$price[0])) ;

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
                                $data_file_name = $this->addImgProduct($id_product , $P);
                                $data_file_name['id_product'] = $id_product;

                                Model::Img_product()->set($data_file_name);
                            }

                            break;

                        case 'unset_product':

                            $id = $P->vars['post']['id'];
                            $arr = Model::Product()->get('id_product='.$id);
                            $dir = PATCH_LOAD.'file/product/'.$arr[0]['id_brand'].'/'.$id;

                            $op = opendir($dir);

                            if($op) {

                                while (false !== ($entry = readdir($op))) {

                                    if($entry == '.' || $entry == '..') {

                                        continue;

                                    } else {

                                        if (file_exists($dir . '/' . $entry)) {

                                            unlink($dir . '/' . $entry);
                                        }
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

                            $id = $P->vars['post']['id_product'];


                            $data_product = [
                                'age'       => $P->vars['post']['age'],
                                'woman'     => $P->vars['post']['woman'] ? $P->vars['post']['woman'] :  0,
                                'man'       => $P->vars['post']['man'] ? $P->vars['post']['man'] :  0
                            ];

                            $arr = [
                                'id_category'           => $P->vars['post']['id_category'],
                                'id_brand'              => $P->vars['post']['id_brand'],
                                'name_product'          => $P->vars['post']['name_product'],
                                'description_product'   => $P->vars['post']['description_product'],
                                'status'                => $P->vars['post']['active'],
                                'article'               => $P->vars['post']['article'],
                                'data_product'          => json_encode($data_product),
                                'mini_desc' => $P->vars['post']['mini_desc'],
                            ];

                            Model::Product()->updata($arr, 'id_product='.$id);

                            break;

                        case 'edit_file_product':

                            $id_product = $P->vars['post']['id_product'];

                            $data_file_name = $this->addImgProduct($id_product , $P);

                            Model::Img_product()->updata($data_file_name, 'id_product='.$id_product);

                            break;

                        case 'edit_price_product':

                            $id_product = $P->vars['post']['id_product'];

                            $arr = [
                                'price_product'     => $P->vars['post']['price_product'],
                                'sale_product'      => json_encode( $P->vars['post']['sale_product'])
                            ];

                            Model::Price()->updata($arr, 'id_product='.$id_product);

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

        $data_product = [
            'age'       => $P->vars['post']['age'],
            'woman'     => $P->vars['post']['woman'] ? $P->vars['post']['woman'] :  0,
            'man'       => $P->vars['post']['man'] ? $P->vars['post']['man'] :  0
        ];

        $arr = [
            'id_category'         => $P->vars['post']['id_category'],
            'id_brand'            => $P->vars['post']['id_brand'],
            'name_product'        => $P->vars['post']['name_product'],
            'description_product' => $P->vars['post']['description_product'],
            'status'              => $P->vars['post']['active'],
            'article'             => $P->vars['post']['article'],
            'data_product'        => json_encode($data_product),
            'mini_desc' => $P->vars['post']['mini_desc'],
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

        return $data_file_name;
    }



    private function getNameData ($id, $tablet) {

        $table = ucfirst($tablet);

        $data = Model::$table()->get('id_'.$tablet.'='.$id);

       return $data[0]['name_'.$tablet];
    }


    public function numPageProduct() {

        $count = $this->countTogglePage();
        $items = '';

        for ($i = 1; $i <= $count; $i++) {

            $items .= $this->include_tmpl('incblock', 'item_num_page_datm', [

                'num'   => $i
            ]);
        }

        return $items;
    }

    private function changeNumPage($numPage) {

        $page = $this->count * ($numPage - 1);

        return $page;
    }

    private function  parseDataProduct($product_data) {

        $data = json_decode($product_data, true);

       return $data;
    }

    private function getInfoProduct($data_order){

        $p = ' ';


        if(!empty($data_order['product'] )) {

            foreach ($data_order['product'] as $id_product) {

                $q = Model::query('SELECT article FROM product WHERE id_product='.$id_product);

                $article = $q->fetch();

                $p .= $this->include_tmpl('datm/order', 'product',[

                    'article'    => $article[0],
                    'id_product' => $id_product,
                    'count'      => $data_order['count'][$id_product]
                ]);
            }
        }

       return $p;
    }
}