<?php

namespace Core;

use PDO;

/**
* Класс Db
* Компонент для работы с базой данных
*/
class Db
{
  /**
  * @var object Соединение с БД
  */
  private $_dbh;
  /**
  * @var object Подготовительный запрос
  */
  private $_stmt;
  /**
  * @var array Параметры запроса для использования в execute()
  */
  private $_params;
  /**
  * @var object Объект текущего класса для singleton
  */
  private static $_instance;

  private function __construct()
  {
    // Загружаем конфигурацию подключения
    $config = include_once ROOT . '/configs/db.php';

    // Устанавливаем соединение
    $this->_dbh = new \PDO(
      'mysql:' .
        'host=' . $config['host'] . ';' .
        'dbname=' . $config['dbname'],
      $config['user'],
      $config['pass']
    );
  }

  /**
  * Реализация шаблона проектирования singleton
  * @return object Текущий объект
  */
  public static function getInstance()
  {
    if (self::$_instance == null) {
      self::$_instance = new self;
    }

    return self::$_instance;
  }

  /**
  * Подготовка запроса к выполнению
  * @param string $sql Запрос
  * @return object Текущий объект
  */
  public function query($sql)
  {
    $this->_stmt = $this->_dbh->prepare($sql);

    return $this;
  }

  /**
  * Получение параметров запроса для использования в execute()
  * @param array $params Параметры запроса
  * @return object Текущий объект
  */
  public function params($params)
  {
    $this->_params = $params;

    return $this;
  }

  /**
  * Получение параметра запроса для использования в bindParam()
  * @param string $param Идентификатор
  * @param string $data Значение
  * @param string $dataType Тип значения
  * @return object Текущий объект
  */
  public function param($param, $data, $dataType)
  {
    $this->_stmt->bindValue($param, $data, $dataType);

    return $this;
  }

  /**
  * Выполнение подготовительного запроса
  * @return boolean Результат выполнения запросв
  */
  public function execute()
  {
    return $this->_stmt->execute($this->_params);
  }

  /**
  * Выполнение подготовительного запроса
  * @return array Ассоциативный массив
  */
  public function row()
  {
    $this->_stmt->execute($this->_params);

    return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
  * Выполнение подготовительного запроса
  * @return mixed Первая ячейка первого столбца
  */
  public function column()
  {
    $this->_stmt->execute($this->_params);

    return $this->_stmt->fetchColumn();
  }
}
