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
    // Verwerk POST-verzoek (formulier verzonden)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize invoer
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      // Data voor validatie en view
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

      // Validatie
      if (empty($data['name'])) {
        $data['name_err'] = 'Vul de familienaam in.';
      } elseif (preg_match('/[0-9]/', $data['name'])) {
        $data['name_err'] = 'De familienaam mag geen cijfers bevatten.';
      }

      if (empty($data['street'])) {
        $data['street_err'] = 'Vul de straat in.';
      } elseif (preg_match('/[0-9]/', $data['street'])) {
        $data['street_err'] = 'De straatnaam mag geen cijfers bevatten.';
      }

      if (empty($data['house_number'])) {
        $data['house_number_err'] = 'Vul het huisnummer in.';
      } elseif (!preg_match('/^[0-9]+$/', $data['house_number'])) {
        $data['house_number_err'] = 'Het huisnummer mag alleen cijfers bevatten.';
      }

      if (empty($data['postal_code'])) {
        $data['postal_code_err'] = 'Vul de postcode in.';
      } elseif (!preg_match('/^[0-9]{4}[A-Z]{2}$/', $data['postal_code'])) {
        $data['postal_code_err'] = 'Vul een geldige postcode in (bijv. 1234AB).';
      }

      if (empty($data['city'])) {
        $data['city_err'] = 'Vul de plaats in.';
      } elseif (preg_match('/[0-9]/', $data['city'])) {
        $data['city_err'] = 'De plaatsnaam mag geen cijfers bevatten.';
      }

      if (empty($data['country'])) {
        $data['country_err'] = 'Vul het land in.';
      }

      // Controleer adresuniekheid
      if (
        empty($data['street_err']) && empty($data['house_number_err']) &&
        empty($data['postal_code_err']) && empty($data['city_err']) && empty($data['country_err'])
      ) {
        if ($this->familyModel->addressExists($data)) {
          $data['address_err'] = 'Er woont al een familie op dit adres.';
        }
      }

      // Debug: controleer $data voordat de view wordt geladen
      if (!empty($data['address_err'])) {
        // var_dump($data);
        // die();
      }

      // Controleer of er geen validatiefouten zijn
      if (
        empty($data['name_err']) && empty($data['street_err']) && empty($data['house_number_err']) &&
        empty($data['postal_code_err']) && empty($data['city_err']) && empty($data['country_err']) &&
        empty($data['address_err'])
      ) {
        // Voeg familie toe aan de database
        if ($this->familyModel->addFamily($data)) {
          flash('family_message', 'Familie succesvol toegevoegd.', 'bg-green-500');
          redirect('family/index');
        } else {
          flash('family_message', 'Er ging iets mis bij het toevoegen van de familie. Probeer het opnieuw.', 'bg-red-500');
          $this->view('family/addFamily', $data);
        }
      } else {
        // Toon formulier met foutmeldingen
        $this->view('family/addFamily', $data);
      }
    } else {
      // Toon leeg formulier bij GET-verzoek
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
}
