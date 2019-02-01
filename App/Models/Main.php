<?php

namespace App\Models;

use Core\Db;

class Main
{
  public static function getTotalRows()
  {
    $sql = '
      SELECT
        count(*)
      FROM
        index_table
    ';

    return Db::getInstance()
      ->query($sql)
      ->column();
  }

  public static function getData($page, $limit)
  {
    $offset = ($page - 1) * $limit;

    $sql = '
      SELECT
        *
      FROM
        index_table
      LIMIT
        :LIMIT
      OFFSET
        :OFFSET
    ';

    return Db::getInstance()
      ->query($sql)
      ->param(':LIMIT', $limit)
      ->param(':OFFSET', $offset)
      ->row();
  }

  public static function setData()
  {
    $sql = '
      INSERT INTO
        index_table (index_1, index_2)
      VALUES (:INDEX_1, :INDEX_2)
    ';

    $params = [
      ':INDEX_1' => '777',
      ':INDEX_2' => '777',
    ];

    return
      Db::getInstance()
        ->query($sql)
        ->params($params)
        ->execute();

  }
}
