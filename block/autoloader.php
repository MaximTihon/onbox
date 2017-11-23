<?php

spl_autoload_register(function ($class) {

    $prefix = 'onbox\\';

    $length = strlen($prefix);

    if(strncmp($prefix, $class, $length) != 0) {

        die('NO CLASS');
    }

    $name_patch = substr($class, $length);

    $file = PATCH_LOAD.str_replace('\\',DIRECTORY_SEPARATOR, $name_patch).'.php';

    if(file_exists($file)) {

        include $file;
    }
});