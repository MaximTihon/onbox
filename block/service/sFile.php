<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22.10.17
 * Time: 10:24
 */

namespace onbox\block\service;


class sFile {

    public static function getFileContent($patchFile) {

        if(file_exists($patchFile)) {

            return file_get_contents($patchFile);

        } else {

            die(404);
        }
    }

    public static function copyFile($file, $dir) {

        if($file['file']['error'] == 0) {

            $typy_img = explode('.',$file['file']['name']);
            $name_file = substr(md5($file['file']['name']),0 , 8).'.'.$typy_img[1];

           if(copy($file['file']['tmp_name'], $dir.'/'.$name_file)) {

               return $name_file;

           } else {

               return false;
           }

        } else {

           die('NO FILE'); //no file
        }
    }


    public static function copyFileAll($files, $dir) {

        $data_img = [];

        if(!empty($files)) {

            foreach ($files as $key => $file) {

                if($file['error'] == 0) {

                    $typy_img = explode('.',$file['name']);
                    $name_file = substr(md5($file['name']),0 , 8).'.'.$typy_img[1];

                    if(copy($file['tmp_name'], $dir.'/'.$name_file)) {

                        $data_img[$key] = $name_file;

                    } else {

                        $data_img[$key] = 'error_copy';
                    }
                }
            }
        }

        return $data_img;
    }



    public static function checkFileAccess($patch) {

        $perms = fileperms($patch);

        switch ($perms & 0xF000) {
            case 0xC000: // сокет
                $info = 's';
                break;
            case 0xA000: // символическая ссылка
                $info = 'l';
                break;
            case 0x8000: // обычный
                $info = 'r';
                break;
            case 0x6000: // файл блочного устройства
                $info = 'b';
                break;
            case 0x4000: // каталог
                $info = 'd';
                break;
            case 0x2000: // файл символьного устройства
                $info = 'c';
                break;
            case 0x1000: // FIFO канал
                $info = 'p';
                break;
            default: // неизвестный
                $info = 'u';
        }

// Владелец
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

// Группа
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

// Мир
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }
}