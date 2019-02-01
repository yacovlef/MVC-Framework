<?php

// FRONT CONTROLLER

// Общие настройки
define('ROOT', dirname(__DIR__));

// Подключение файлов
require_once ROOT . '/Core/autoload.php';
require_once ROOT . '/Core/helpers.php';

// Вызов Router
$router = new \Core\Router();
$router->run();
