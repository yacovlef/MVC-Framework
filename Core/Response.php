<?php

namespace Core;

/**
* Класс Response
* Компонент для работы с ответами
*/
class Response
{
  /**
  * Возврат представления
  * @param string $view Путь
  * @param string $data Данные
  * @return void
  */
  public static function view($view, $data = [])
  {
    extract($data);

    $path = ROOT . '/App/views/' . $view . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
  }

  /**
  * Возврат в формате json
  * @param string $data Данные
  * @return void
  */
  public static function json($data)
  {
    echo json_encode($data);
  }

  /**
  * Возврат перенаправления
  * @param string $url url
  * @return void
  */
  public static function redirect($url)
  {
    header('location: ' . $url);
  }

  /**
  * Возврат ошибки
  * @param integer $code Код ошибки
  * @return void
  */
  public static function error($code)
  {
    http_response_code($code);

    self::view('errors/error', ['code' => $code]);
  }
}
