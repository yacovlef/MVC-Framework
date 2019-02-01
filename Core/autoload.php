<?php

/**
* Автозагрузка классов
* @param string $class название класса
* @return void
*/
spl_autoload_register(function ($class) {

    $path = ROOT . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
});
