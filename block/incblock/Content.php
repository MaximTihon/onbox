<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27.11.17
 * Time: 23:02
 */

namespace onbox\block\incblock;
use onbox\block\vs\Model;

trait Content
{
    public function getContent($id_content) {

        if($id_content) {

           $cont = Model::Content()->get('id_content='.$id_content);

           return urldecode($cont[0]['content']);
        }

    }
}