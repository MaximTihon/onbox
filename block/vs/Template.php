<?php

namespace onbox\block\vs;

use onbox\block\service\sCreatingObj;
use onbox\block\service\sFile;
use onbox\block\service\sParseTmpl;

class Template {

    public $vars = [];
    public static $answer = array();

    public function include_tmpl($dir, $name_file, $arr = array()) {

        $patch_name = '/var/www/onbox.dev/template/content/'.$dir.'/'.$name_file.'.tmpl';

        if(file_exists($patch_name)) {

            $html = sFile::getFileContent($patch_name);

            foreach ($arr as $key => $val) {

                $html = str_replace('{'.$key.'}', $val, $html);
            }

            $html = preg_replace('/{[a-z_\.\/]+\}/', '', $html);

            return $html;
        }
    }
}