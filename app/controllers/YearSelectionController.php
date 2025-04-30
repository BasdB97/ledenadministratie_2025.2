<?php

class YearSelectionController extends Controller
{
  private $bookyearModel;

  public function __construct()
  {
    $this->bookyearModel = $this->model('Bookyear');
  }

  public function index()
  {
    $currentYear = date('Y');
    $bookyears = $this->bookyearModel->getAllBookyears();
    $data = [
      'currentYear' => $currentYear,
      'bookyears' => $bookyears
    ];
    $this->view('yearselection/index', $data);
  }
}
