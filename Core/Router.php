<?php

namespace Core;

use Core\Response;

/**
* Класс Router
* Компонент для работы с маршрутами
*/
class Router
{
  /**
  * @var array Конфигурация маршрутизации
  */
  private $_routes;
  /**
  * @var string Название контроллера
  */
  private $_controller;
  /**
  * @var string Название действия
  */
  private $_action;
  /**
  * @var array Параметры запроса
  */
  private $_params;

  public function __construct()
  {
    // Загружаем конфигурацию маршрутизации
    $this->_routes = require_once ROOT . '/configs/routes.php';

    // Проверяем запрос
    $this->_match();
  }

  /**
  * Проверка запроса
  * @return boolean Результат проверки запроса
  */
  private function _match()
  {
    // Получение запроса
    $uri = trim($_SERVER['REQUEST_URI'], '/');

    foreach ($this->_routes as $pattern => $params) {
      // Сравнение запроса с маршрутом
      if (preg_match("#^$pattern$#", $uri, $matches)) {
        // Определяем контроллер, действие и параметры запроса
        [$controllerName, $actionName, $middleware] = preg_split("#@|:#", $params);

        $this->_controller = '\App\Controllers\\' . ucfirst($controllerName) . 'Controller';
        $this->_action = 'action' . ucfirst($actionName);
        $this->_params = array_splice($matches, 1);
      }
    }
  }

  /**
  * Обработка запроса
  * @return void
  */
  public function run()
  {
    // Создание экземпляра класса контроллера
    if (class_exists($this->_controller)) {
      $controller = new $this->_controller;

      // Вызов метода действия и передача параметров
      if (method_exists($this->_controller, $this->_action)) {
        call_user_func_array([$controller, $this->_action], $this->_params);

        return;
      }
    }

    // Маршрут НЕ найден
    Response::error(404);
  }
}
