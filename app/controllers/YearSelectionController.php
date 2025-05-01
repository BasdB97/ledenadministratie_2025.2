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
      $selectedYear = (int)$_POST['year'];
      $bookyear = $this->bookyearModel->getBookyearByYear($selectedYear);
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      setSession('selectedYear', $bookyear->year);
      setSession('bookyear_id', $bookyear->id);
      redirect('dashboard/index');
    } else {
      redirect('yearselection/index');
    }
  }
}
