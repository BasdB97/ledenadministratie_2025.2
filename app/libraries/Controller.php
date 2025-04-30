<?php

class Controller
{
  public function model($model)
  {
    require_once '../app/models/' . $model . '.php';

    return new $model();
  }

  public function view($view, $data = [])
  {
    // Check for view file
    if (file_exists('../app/views/' . $view . '.php')) {
      require_once '../app/views/' . $view . '.php';
    } else {
      // View does not exist
      die('View does not exist');
    }
  }
}
