<?php

class FamilyMemberController extends Controller
{
  private $familyMemberModel;
  private $familyModel;
  private $bookyearModel;
  private $bookyear_id;

  public function __construct()
  {
    $this->familyMemberModel = $this->model('FamilyMember');
    $this->familyModel = $this->model('Family');
    $this->bookyearModel = $this->model('Bookyear');
    $this->bookyear_id = $this->bookyearModel->getActiveBookyear()->id;
  }

  public function addFamilyMember($familyId)
  {
    $family = $this->familyModel->getFamilyById($familyId);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data = [
        'family_id' => $familyId,
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => $family->name,
        'date_of_birth' => trim($_POST['date_of_birth'] ?? ''),
        'first_name_err' => '',
        'date_of_birth_err' => '',
        'bookyear_id' => $this->bookyear_id
      ];

      // Valideer invoer
      if (empty($data['first_name'])) {
        $data['first_name_err'] = 'Vul een naam in.';
      } elseif (preg_match('/[0-9]/', $data['first_name'])) {
        $data['first_name_err'] = 'De voornaam mag geen cijfers bevatten.';
      }

      if (empty($data['date_of_birth'])) {
        $data['date_of_birth_err'] = 'Vul een geboortedatum in.';
      } else {
        $birthDate = DateTime::createFromFormat('Y-m-d', $data['date_of_birth']);
        if (!$birthDate || $birthDate->format('Y-m-d') !== $data['date_of_birth']) {
          $data['date_of_birth_err'] = 'Vul een geldige geboortedatum in (bijv. 1980-10-10).';
        } elseif ($birthDate > new DateTime()) {
          $data['date_of_birth_err'] = 'Geboortedatum kan niet in de toekomst liggen.';
        } elseif ((new DateTime())->diff($birthDate)->y > 120) {
          $data['date_of_birth_err'] = 'Leeftijd mag niet hoger zijn dan 120 jaar.';
        }
      }

      // Bereken leeftijd en member_type
      if (empty($data['date_of_birth_err'])) {
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y;
        $data['age'] = $age;
        switch (true) {
          case ($age < 8):
            $data['member_type_id'] = 1;
            break;
          case ($age >= 8 && $age <= 12):
            $data['member_type_id'] = 2;
            break;
          case ($age >= 13 && $age <= 17):
            $data['member_type_id'] = 3;
            break;
          case ($age >= 18 && $age <= 50):
            $data['member_type_id'] = 4;
            break;
          case ($age >= 51):
            $data['member_type_id'] = 5;
            break;
          default:
            $data['date_of_birth_err'] = 'Ongeldige leeftijd.';
        }

        // Haal kortingspercentage op en bereken contributiebedrag
        if (empty($data['date_of_birth_err'])) {
          $discount = $this->familyMemberModel->getDiscountPercentage($data['member_type_id']);
          $data['contribution_amount'] = 100 * (1 - $discount / 100);
        }
      }

      if (empty($data['first_name_err']) && empty($data['date_of_birth_err'])) {
        if ($this->familyMemberModel->addFamilyMemberWithContribution($data)) {
          flash('family_member_message', 'Familielid succesvol toegevoegd.', 'alert-success');
          redirect('family/familyDetails/' . $familyId);
        } else {
          flash('family_member_message', 'Er ging iets mis bij het toevoegen van het familielid en contributie.', 'alert-danger');
          $this->view('family-member/addFamilyMember', $data);
        }
      } else {
        $this->view('family-member/addFamilyMember', $data);
      }
    } else {
      $family = $this->familyModel->getFamilyById($familyId);
      if (!$family) {
        flash('family_member_message', 'Familie niet gevonden.', 'alert-danger');
        redirect('family/index');
      }
      $data = [
        'family_id' => $familyId,
        'first_name' => '',
        'last_name' => $family->name,
        'date_of_birth' => '',
        'first_name_err' => '',
        'last_name_err' => '',
        'date_of_birth_err' => '',
        'family_name_err' => ''
      ];
      $this->view('family-member/addFamilyMember', $data);
    }
  }

  public function editFamilyMember($memberId)
  {
    $member = $this->familyMemberModel->getFamilyMemberById($memberId);
    if (!$member) {
      flash('family_member_message', 'Familielid niet gevonden.', 'alert-danger');
      redirect('family/index');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data = [
        'id' => $memberId,
        'family_id' => $member->family_id,
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => $member->last_name,
        'date_of_birth' => trim($_POST['date_of_birth'] ?? ''),
        'bookyear_id' => $this->bookyear_id,
        'first_name_err' => '',
        'date_of_birth_err' => ''
      ];

      // Valideer invoer
      if (empty($data['first_name'])) {
        $data['first_name_err'] = 'Vul een naam in.';
      } elseif (preg_match('/[0-9]/', $data['first_name'])) {
        $data['first_name_err'] = 'De voornaam mag geen cijfers bevatten.';
      }

      if (empty($data['date_of_birth'])) {
        $data['date_of_birth_err'] = 'Vul een geboortedatum in.';
      } else {
        $birthDate = DateTime::createFromFormat('Y-m-d', $data['date_of_birth']);
        if (!$birthDate || $birthDate->format('Y-m-d') !== $data['date_of_birth']) {
          $data['date_of_birth_err'] = 'Vul een geldige geboortedatum in (bijv. 1980-10-10).';
        } elseif ($birthDate > new DateTime()) {
          $data['date_of_birth_err'] = 'Geboortedatum kan niet in de toekomst liggen.';
        } elseif ((new DateTime())->diff($birthDate)->y > 120) {
          $data['date_of_birth_err'] = 'Leeftijd mag niet hoger zijn dan 120 jaar.';
        }
      }

      // Bereken leeftijd en member_type
      if (empty($data['date_of_birth_err'])) {
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y;
        $data['age'] = $age;
        switch (true) {
          case ($age < 8):
            $data['member_type_id'] = 1;
            break;
          case ($age >= 8 && $age <= 12):
            $data['member_type_id'] = 2;
            break;
          case ($age >= 13 && $age <= 17):
            $data['member_type_id'] = 3;
            break;
          case ($age >= 18 && $age <= 50):
            $data['member_type_id'] = 4;
            break;
          case ($age >= 51):
            $data['member_type_id'] = 5;
            break;
          default:
            $data['date_of_birth_err'] = 'Ongeldige leeftijd.';
        }

        // Haal kortingspercentage op en bereken contributiebedrag
        if (empty($data['date_of_birth_err'])) {
          $discount = $this->familyMemberModel->getDiscountPercentage($data['member_type_id']);
          $data['contribution_amount'] = 100 * (1 - $discount / 100);
        }
      }

      if (empty($data['first_name_err']) && empty($data['date_of_birth_err'])) {
        if ($this->familyMemberModel->updateFamilyMember($data)) {
          flash('family_member_message', 'Familielid succesvol bijgewerkt.', 'alert-success');
          redirect('family/familyDetails/' . $data['family_id']);
        } else {
          flash('family_member_message', 'Er ging iets mis bij het bijwerken van het familielid.', 'alert-danger');
          $this->view('family-member/editFamilyMember', $data);
        }
      } else {
        $this->view('family-member/editFamilyMember', $data);
      }
    } else {
      $data = [
        'id' => $memberId,
        'family_id' => $member->family_id,
        'first_name' => $member->first_name,
        'last_name' => $member->last_name,
        'date_of_birth' => $member->date_of_birth,
        'first_name_err' => '',
        'date_of_birth_err' => ''
      ];
      $this->view('family-member/editFamilyMember', $data);
    }
  }

  public function deleteFamilyMember($memberId)
  {
    $member = $this->familyMemberModel->getFamilyMemberById($memberId);
    if (!$member) {
      flash('family_member_message', 'Familielid niet gevonden.', 'alert-danger');
      redirect('family/index');
    }

    $familyId = $member->family_id;
    if ($this->familyMemberModel->deleteFamilyMember($memberId)) {
      flash('family_member_message', 'Familielid succesvol verwijderd.', 'alert-success');
      redirect('family/familyDetails/' . $familyId);
    } else {
      flash('family_member_message', 'Er ging iets mis bij het verwijderen van het familielid.', 'alert-danger');
      redirect('family/familyDetails/' . $familyId);
    }
  }
}
