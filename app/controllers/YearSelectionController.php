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

  public function selectYear()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      setSession('selectedYear', (int)$_POST['year']);
      redirect('dashboard/index/');
    } else {
      redirect('yearselection/index');
    }
  }
}
