<?php

namespace Core;

/**
* Класс Validator
* Компонент для валидации данных
*/
class Validator
{
  /**
  * @var array Данные запроса
  */
  private $_request;
  /**
  * @var array Правила валидации
  */
  private $_rules;
  /**
  * @var array Ошибки
  */
  public $errors;

  /**
  * Создание экземпляра класса валидации
  * @param array $request Данные запроса
  * @param array $rules Правила валидации
  */
  public function __construct($request, $rules)
  {
    $this->_request = $request;

    $this->_rules = $rules;

    $this->_match();
  }

  /**
  * Проверка данных по правилам валидации
  * @return void
  */
  private function _match()
  {
    // Перебираем параметры запроса
    foreach ($this->_rules as $requestParam => $rulesStr) {
      // Разбираем на правила
      $rules = explode('|', $rulesStr);

      // Перебираем правила
      foreach ($rules as $rule) {
        // Есть аргумены
        if (strpos($rule, ':')) {
          // Разбиваем на правило и аргумены
          [$ruleName, $ruleArgs] = explode(':', $rule);

          // Разбиваем аргументы
          $ruleArgs = explode(',', $ruleArgs);

          // Выполняем проверку
          if (method_exists(__CLASS__, $ruleName)) {
            $this->$ruleName($requestParam, $this->_request[$requestParam], ...$ruleArgs);
          }
        // Нет аргументов
        } else {
          // Выполняем проверку
          if (method_exists(__CLASS__, $rule)) {
            $this->$rule($requestParam, $this->_request[$requestParam]);
          }
        }
      }
    }
  }

  /**
  * Данные не должны быть пустыми
  * @param string $requestParam Параметр запроса
  * @return object Текущий объект
  */
  private function required($requestParam, $data)
  {
    if (empty($data)) {
      $this->errors[$requestParam][] = 'Поле обязательно для заполнения.';
    }
  }

  private function min($requestParam, $data, $min)
  {
    if (mb_strlen($data) < $min) {
      $this->errors[$requestParam][] = 'Поле не может быть менее ' . $min . ' символов.';
    }
  }

  private function max($requestParam, $data, $max)
  {
    if (mb_strlen($data) > $max) {
      $this->errors[$requestParam][] = 'Поле не может быть более ' . $max . ' символов.';
    }
  }

  private function unique($requestParam, $data, $table, $column)
  {
    $db = Db::getInstance();

    $sql = "
      SELECT
        count(*)
      FROM
        $table
      WHERE
        $column = :DATA
    ";

    $data = [
      ':DATA' => $data
    ];

    $unique = (boolean) $db
      ->query($sql)
      ->params($data)
      ->column();

    if ($unique) {
      $this->errors[$requestParam][] = 'Такое значение поля уже существует.';
    }
  }
}
