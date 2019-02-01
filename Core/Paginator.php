<?php

namespace Core;

/**
* Класс Pagination
* Компонент для работы с постраничной навигацией
*/
class Paginator
{
  /**
  * @var Ссылок навигации на страницу
  */
  private $max = 10;
  /**
  * @var Ключ для GET, в который пишется номер страницы
  */
  private $index = 'page=';
  /**
  * @var Текущая страница
  */
  private $currentPage;
  /**
  * @var Общее количество записей
  */
  private $total;
  /**
  * @var Записей на страницу
  */
  private $limit;

  /**
  * Запуск необходимых данных для навигации
  * @param type $total Общее количество записей
  * @param type $currentPage Номер текущей страницы
  * @param type $limit Количество записей на страницу
  */
  public function __construct($total, $currentPage, $limit = 5)
  {
    // Устанавливаем общее количество записей
    $this->total = $total;
    // Устанавливаем количество записей на страницу
    $this->limit = $limit;

    // Устанавливаем количество страниц
    $this->amount();
    // Устанавливаем номер текущей страницы
    $this->setCurrentPage($currentPage);
  }

  /**
  * Рендеринг постраничной навигации при преобразовании объекта в строку
  * * @return type HTML-код
  */
  public function __toString()
  {
    return $this->get();
  }

  /**
  *  Рендеринг постраничной навигации
  * @return type HTML-код
  */
  public function get()
  {
    // Для записи ссылок
    $links = null;

    // Получаем ограничения для цикла
    $limits = $this->limits();

    $html = '<ul class="pagination">' . "\n";

    // Генерируем ссылки
    for ($page = $limits['start']; $page <= $limits['end']; $page++) {
      // Если текущая это текущая страница, ссылки нет и добавляется класс active
      if ($page == $this->currentPage) {
        $links .= "\t" . '<li class="active"><a href="#">' . $page . '</a></li>' . "\n";
      } else {
        // Иначе генерируем ссылку
        $links .= $this->generateHtml($page);
      }
    }

    // Если ссылки создались
    if (!is_null($links)) {
      // Если текущая страница не первая
      if ($this->currentPage > 1) {
        // Создаём ссылку "На первую"
        $links = $this->generateHtml(1, '&lt;') . $links;
      }
      // Если текущая страница не первая
      if ($this->currentPage < $this->amount) {
        // Создаём ссылку "На последнюю"
        $links .= $this->generateHtml($this->amount, '&gt;');
      }
    }

    $html .= $links . '</ul>' . "\n";

    return $html;
  }

  /**
  * Для генерации HTML-кода ссылки
  * @param integer $page Номер страницы
  * @param string $text Текст ссылки
  * @return string HTML-код ссылки
  */
  private function generateHtml($page, $text = null)
  {
    // Если текст ссылки не указан
    if (!$text) {
      // Указываем, что текст-цифра страницы
      $text = $page;
    }

    $currentURI = $_SERVER['REQUEST_URI'];
    $currentURI = preg_replace("#$this->index[0-9]+#", '#', $currentURI);

    // Формируем HTML-код ссылки и возвращаем
    return "\t" . '<li><a href="' . str_replace('#', $this->index . $page ,$currentURI) . '">' . $text . '</a></li>' . "\n";
  }

  /**
  * Диапазон страниц для рендеринга
  * @return array номер страницы начала и конца
  */
  private function limits()
  {
    // Вычисляем ссылки слева (чтобы активная ссылка была посередине)
    $left = $this->currentPage - round($this->max / 2);

    // Вычисляем начало отсчёта
    $start = $left > 0 ? $left : 1;

    // Если впереди есть как минимум $this->max страниц
    if ($start + $this->max <= $this->amount) {
      // Назначаем конец цикла вперёд на $this->max страниц или просто на минимум
      $end = $start > 1 ? $start + $this->max : $this->max;
    } else {
      // Конец-общее количество страниц
      $end = $this->amount;
      // Начало-минус $this->max от конца
      $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
    }

    return ['start' => $start, 'end' => $end];
  }

  /**
  * Установка текущей страницы
  * @param integer $currentPage Текщая страница
  * @return void
  */
  private function setCurrentPage($currentPage)
  {
    // Получаем номер страницы
    $this->currentPage = $currentPage;

    // Если текущая страница больше нуля
    if ($this->currentPage > 0) {
      // Если текущая страница меньше общего количества страниц
      if ($this->currentPage > $this->amount) {
        // Устанавливаем страницу на последнюю
        $this->currentPage = $this->amount;
      }
    } else {
      // Устанавливаем страницу на первую
      $this->currentPage = 1;
    }
  }

  /**
  * Вычесляем кол-во страниц
  * @return integer число страниц
  */
  private function amount()
  {
    $this->amount = ceil($this->total / $this->limit);
  }
}

/*

// Действие
public function actionTest()
{
  $page = $_GET['page'] ?? null;

  $limit = 5;

  $total = Main::getTotalRows();

  $data = Main::getData($page, $limit);

  $pagination = new \Core\Pagination($total, $page);

  $data = [
    'pagination' => $pagination,
    'data' => $data
  ];

  Response::view('main/index', $data);
}

// Метод
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
*/
