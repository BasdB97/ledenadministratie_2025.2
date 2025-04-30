<?php

class DashboardController extends Controller
{
  private $bookyearModel;
  private $archiveModel;
  private $familyMemberModel;
  private $contributionModel;

  public function __construct()
  {
    $this->bookyearModel = $this->model('Bookyear');
    $this->archiveModel = $this->model('Archive');
    $this->familyMemberModel = $this->model('FamilyMember');
    $this->contributionModel = $this->model('Contribution');

    // Get active book year
    $activeBookyear = $this->bookyearModel->getActiveBookyear();

    if ($activeBookyear) {
      // Check if there are contributions from other years
      if ($this->archiveModel->hasContributionsFromOtherYears($activeBookyear->id)) {
        // Archive contributions from other years
        $this->archiveModel->archiveContributions($activeBookyear->id);
      }

      // Get all family members
      $familyMembers = $this->familyMemberModel->getAllFamilyMembers();

      foreach ($familyMembers as $member) {
        // Calculate current age
        $currentAge = $this->familyMemberModel->calculateAge($member->date_of_birth);

        // Check if member type needs to be updated based on age
        $newMemberType = $this->determineMemberType($currentAge);
        if ($newMemberType != $member->member_type) {
          // Update member type
          $this->familyMemberModel->updateMemberType($member->id, $newMemberType);
        }

        // Check if contribution exists for current book year
        $contribution = $this->contributionModel->getContributionByMember($member->id, $activeBookyear->id);

        if ($contribution) {
          // Update existing contribution
          $newAmount = $this->contributionModel->calculateContribution($currentAge, $newMemberType, $activeBookyear->id);
          $this->contributionModel->updateContribution($contribution->id, $currentAge, $newAmount);
        } else {
          // Create new contribution
          $newAmount = $this->contributionModel->calculateContribution($currentAge, $newMemberType, $activeBookyear->id);
          $this->contributionModel->createContribution($member->id, $activeBookyear->id, $currentAge, $newMemberType, $newAmount);
        }
      }
    }
  }

  private function determineMemberType($age)
  {
    // Default to standard member type (1)
    $memberType = 1;

    // Check age-based member types
    if ($age < 18) {
      // Family member type (4)
      $memberType = 4;
    } elseif ($age >= 18 && $age <= 25) {
      // Student member type (2)
      $memberType = 2;
    } elseif ($age >= 65) {
      // Honorary member type (3)
      $memberType = 3;
    }

    return $memberType;
  }

  public function index()
  {
    $this->view('dashboard/index');
  }
}
