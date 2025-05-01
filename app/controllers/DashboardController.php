<?php

class DashboardController extends Controller
{
  private $bookyearModel;
  private $familyModel;
  private $contributionModel;
  private $familyMemberModel;

  public function __construct()
  {
    $this->bookyearModel = $this->model('Bookyear');
    $this->familyModel = $this->model('Family');
    // $this->contributionModel = $this->model('Contribution');
    $this->familyMemberModel = $this->model('FamilyMember');
  }

  public function index()
  {
    $selectedYear = $_SESSION['selectedYear'];
    $bookyear = $this->bookyearModel->getBookyearByYear($selectedYear);
    $familyMembers = $this->familyMemberModel->getMembersWithContributionsByBookyear($bookyear->id);

    $data = [
      'bookyear' => $bookyear,
      'title' => 'Dashboard',
      'familyMembers' => $familyMembers
    ];

    $this->view('dashboard/index', $data);
  }
}
