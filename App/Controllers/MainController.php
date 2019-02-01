<?php

namespace App\Controllers;

use Core\Response;
use Core\Validator;

class MainController
{
  public function actionIndex()
  {
    $request = [
      'firstName' => 'Алексей',
      'lastName' => 'Яковлев',
      'index' => 'index1'
    ];

    $rules = [
      'firstName' => 'required|min:2|max:5',
      'lastName' => 'required|min:7|max:10',
      'index' => 'unique:index_table,index_1'
    ];

    $validation = new Validator($request, $rules);

    dump($validation->errors);

    Response::view('main/index');
  }

  public function actionTest()
  {

  }
}
