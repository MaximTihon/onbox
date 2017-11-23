<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 21.10.17
 * Time: 17:21
 */

namespace onbox\block\vs;

use onbox\block\service\sCreatingObj;
use onbox\block\service\sFile;
use onbox\block\service\sInitBase;
use onbox\block\service\sParseTmpl;

class IncTmpl {

    public $tmpl_main;
    public $tmpl_header;
    public $tmpl_footer;
    public $content = array();
    public $html;


    public function showTmpl() {

        $this->getTmpl();

        echo $this->html;
    }

    private function getTmpl() {

        $processor = sInitBase::incBaseDoc('processor.ini');

        $P = new InitData();

        $this->getDataTmpl($P, $processor);

        $main_tmpl = $this->getMainTmpl();

        $this->parseMainTmpl($main_tmpl, $P);

    }

    private function getDataTmpl($P, $processor) {

        if(array_key_exists($P->object, $processor)) {

            $this->tmpl_main   = $processor[$P->object]['main'];
            $this->tmpl_header = $processor[$P->object]['header'] ? $processor[$P->object]['header'] : 'header' ;
            $this->tmpl_footer = $processor[$P->object]['footer'] ? $processor[$P->object]['footer'] : 'footer' ;
            $this->content     = $processor[$P->object]['content'];

        } else {

            die('404');
            //Browser::go('404');
        }

        return $this;
    }



    private function getMainTmpl() {

       $patchFile = $_SERVER['DOCUMENT_ROOT'].'template/main/'.$this->tmpl_main.'.tmpl';

        return sFile::getFileContent($patchFile);
    }

    private function parseMainTmpl($main_tmpl, $P) {

        preg_match_all("/{([a-z_\.\/]+)\}+/", $main_tmpl, $v);



        $arr = [
            'header'  => $this->incBlockTmpl($this->tmpl_header, $P,'header'),
            'content' => $this->incContentBlock($P),
            'footer'  => $this->incBlockTmpl($this->tmpl_footer, $P, 'footer')
        ];

         $this->html = str_replace($v[0], $arr, $main_tmpl);

    }


    private function incBlockTmpl($object, $P = null, $block = null) {

        $obj = sCreatingObj::getInstance($object, $block);

        $patch_class = get_class($obj);

        if($patch_class) {

           $lenght_prefix = strlen(INC_BLOCK_TMPL);

           $nameTmpl = substr($patch_class, $lenght_prefix);

           $file = $_SERVER['DOCUMENT_ROOT'].'template/'.strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $nameTmpl)).'.tmpl';

           $content = sFile::getFileContent($file);

           return sParseTmpl::parseTmpl($content, $obj, $P);

        };
    }

    private function incContentBlock($P) {

        $html = '';

        $content_arr = explode(';' , $this->content);

        foreach ($content_arr as $item) {

            $obj     = sCreatingObj::getInstance($item);

            $file    = $_SERVER['DOCUMENT_ROOT'].'template/content/'.$item.'.tmpl';

            $content = sFile::getFileContent($file);

            $html .= sParseTmpl::parseTmpl($content, $obj, $P);
        }

        return $html;
    }
}