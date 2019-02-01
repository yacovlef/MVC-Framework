<?php

/**
* Удобо читаемый var_dump()
* @param mixed $data Данные
* @return void
*/
function dump($data)
{
  echo '<pre>';
    var_dump($data);
  echo '</pre>';
}

/**
* Удобо читаемый var_dump() с прекращением выполнения скрипта
* @param mixed $data Данные
* @return void
*/
function dd($data)
{
  echo '<pre>';
    var_dump($data);
  echo '</pre>';

  die;
}

/**
* Подключение представления
* @param string $view представление
* @return void
*/
function inc($view)
{
  require ROOT . '/App/views/' . $view . '.php';
}
