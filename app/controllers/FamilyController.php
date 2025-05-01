<?php

class FamilyController extends Controller
{
  private $familyModel;

  public function __construct()
  {
    $this->familyModel = $this->model('Family');
  }

  public function index()
  {
    $families = $this->familyModel->getAllFamilies();
    $data = [
      'families' => $families
    ];
    $this->view('family/index', $data);
  }

  public function addFamily()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data = [
        'name' => trim($_POST['name'] ?? ''),
        'street' => trim($_POST['street'] ?? ''),
        'house_number' => trim($_POST['house_number'] ?? ''),
        'postal_code' => trim($_POST['postal_code'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'name_err' => '',
        'street_err' => '',
        'house_number_err' => '',
        'postal_code_err' => '',
        'city_err' => '',
        'country_err' => '',
        'address_err' => ''
      ];

      $data = $this->validateForm($data);
      if (
        empty($data['name_err']) && empty($data['street_err']) && empty($data['house_number_err']) &&
        empty($data['postal_code_err']) && empty($data['city_err']) && empty($data['country_err']) &&
        $this->familyModel->addressExists($data)
      ) {
        $data['address_err'] = 'Er woont al een familie op dit adres.';
      }

      if ($this->checkErrors($data)) {
        if ($this->familyModel->addFamily($data)) {
          flash('family_message', 'Familie succesvol toegevoegd.', 'alert-success');
          redirect('family/index');
        } else {
          flash('family_message', 'Er ging iets mis bij het toevoegen van de familie. Probeer het opnieuw.', 'alert-danger');
          $this->view('family/addFamily', $data);
        }
      } else {
        $this->view('family/addFamily', $data);
      }
    } else {
      $data = [
        'name' => '',
        'street' => '',
        'house_number' => '',
        'postal_code' => '',
        'city' => '',
        'country' => 'Nederland',
        'name_err' => '',
        'street_err' => '',
        'house_number_err' => '',
        'postal_code_err' => '',
        'city_err' => '',
        'country_err' => '',
        'address_err' => ''
      ];
      $this->view('family/addFamily', $data);
    }
  }

  public function editFamily($id)
  {
    $family = $this->familyModel->getFamilyById($id);
    if (!$family) {
      flash('family_message', 'Familie niet gevonden.', 'alert-danger');
      redirect('family/index');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data = [
        'id' => $id,
        'family' => $family,
        'name' => trim($_POST['name'] ?? ''),
        'street' => trim($_POST['street'] ?? ''),
        'house_number' => trim($_POST['house_number'] ?? ''),
        'postal_code' => trim($_POST['postal_code'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'name_err' => '',
        'street_err' => '',
        'house_number_err' => '',
        'postal_code_err' => '',
        'city_err' => '',
        'country_err' => '',
        'address_err' => ''
      ];

      $data = $this->validateForm($data);

      if ($this->checkErrors($data)) {
        if ($this->familyModel->updateFamily($data)) {
          flash('family_message', 'Familie succesvol bijgewerkt.', 'alert-success');
          redirect('family/index');
        } else {
          flash('family_message', 'Er ging iets mis bij het bijwerken van de familie. Probeer het opnieuw.', 'alert-danger');
          $this->view('family/editFamily', $data);
        }
      } else {
        $this->view('family/editFamily', $data);
      }
    } else {
      $data = [
        'id' => $id,
        'family' => $family,
        'name' => $family->name,
        'street' => $family->street,
        'house_number' => $family->house_number,
        'postal_code' => $family->postal_code,
        'city' => $family->city,
        'country' => $family->country,
        'name_err' => '',
        'street_err' => '',
        'house_number_err' => '',
        'postal_code_err' => '',
        'city_err' => '',
        'country_err' => '',
        'address_err' => ''
      ];
      $this->view('family/editFamily', $data);
    }
  }

  public function deleteFamily($id)
  {
    $family = $this->familyModel->getFamilyById($id);
    if (!$family) {
      flash('family_message', 'Familie niet gevonden.', 'alert-danger');
      redirect('family/index');
    }

    if ($this->familyModel->deleteFamily($id)) {
      flash('family_message', 'Familie en bijbehorende gegevens succesvol verwijderd.', 'alert-success');
      redirect('family/index');
    } else {
      flash('family_message', 'Er ging iets mis bij het verwijderen van de familie.', 'alert-danger');
      redirect('family/index');
    }
  }

  public function validateForm($data)
  {
    if (empty($data['name'])) {
      $data['name_err'] = 'Vul een naam in.';
    } elseif (preg_match('/[0-9]/', $data['name'])) {
      $data['name_err'] = 'De familienaam mag geen cijfers bevatten.';
    }

    if (empty($data['street'])) {
      $data['street_err'] = 'Vul een straatnaam in.';
    } elseif (preg_match('/[0-9]/', $data['street'])) {
      $data['street_err'] = 'De straatnaam mag geen cijfers bevatten.';
    }

    if (empty($data['house_number'])) {
      $data['house_number_err'] = 'Vul een huisnummer in.';
    } elseif (!preg_match('/^[0-9]+$/', $data['house_number'])) {
      $data['house_number_err'] = 'Het huisnummer mag alleen cijfers bevatten.';
    }

    if (empty($data['postal_code'])) {
      $data['postal_code_err'] = 'Vul een postcode in.';
    } elseif (!preg_match('/^[0-9]{4}[A-Z]{2}$/', $data['postal_code'])) {
      $data['postal_code_err'] = 'Vul een geldige postcode in (bijv. 1234AB).';
    }

    if (empty($data['city'])) {
      $data['city_err'] = 'Vul een plaats in.';
    } elseif (preg_match('/[0-9]/', $data['city'])) {
      $data['city_err'] = 'De plaatsnaam mag geen cijfers bevatten.';
    }

    if (empty($data['country'])) {
      $data['country_err'] = 'Vul het land in.';
    }

    return $data;
  }

  public function checkErrors($data)
  {
    if (
      empty($data['name_err']) && empty($data['street_err']) && empty($data['house_number_err']) &&
      empty($data['postal_code_err']) && empty($data['city_err']) && empty($data['country_err']) &&
      empty($data['address_err'])
    ) {
      return true;
    }
    return false;
  }
}
