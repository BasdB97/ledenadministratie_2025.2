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
    $familyMembers = $this->familyMemberModel->getMembersWithContributionsByBookyear($bookyear->id, null);

    // Maak een unieke lijst van families op basis van family_id
    $families = [];
    foreach ($familyMembers as $member) {
      $familyId = $member->family_id;
      if (!isset($families[$familyId])) {
        $families[$familyId] = $member; // Sla het volledige member-object op
      }
    }

    // Sorteer families op totale contributie (hoog naar laag)
    $families = array_values($families); // Converteer naar geïndexeerde array
    usort($families, function ($a, $b) {
      return $b->total_contribution <=> $a->total_contribution;
    });

    $data = [
      'bookyear' => $bookyear,
      'title' => 'Dashboard',
      'families' => $families
    ];

    $this->view('dashboard/index', $data);
  }
}
