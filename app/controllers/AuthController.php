<?php

class AuthController extends Controller
{
  private $userModel;

  public function __construct()
  {
    // Laad de User model
    $this->userModel = $this->model('Users');
  }

  public function login()
  {
    // Check voor POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // Ingevoerde gegevens schoonmaken en opslaan in $data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data = [
        'username' => trim($_POST['username']),
        'password' => trim($_POST['password']),
        'error' => '',
      ];

      // Controleer of username en wachtwoord zijn ingevuld
      if (empty($data['username'])) {
        $data['error'] = 'Vul een gebruikersnaam in';
      }
      if (empty($data['password'])) {
        $data['error'] = 'Vul een wachtwoord in';
      }

      // Zoek de gebruiker op gebruikersnaam
      if ($this->userModel->findUserByUsername($data['username'])) {
        // Gebruiker gevonden en geen foutmeldingen
        if (empty($data['error'])) {
          // Log de gebruiker in
          $loggedInUser = $this->userModel->login($data['username'], $data['password']);
          // Als de gebruiker is ingelogd, stel de sessie in
          if ($loggedInUser) {
            setSession('user_id', $loggedInUser->id);
            setSession('username', $loggedInUser->username);
            setSession('user_role', $loggedInUser->role);
            setSession('logged_in', true);
            // Redirect to year selection screen instead of dashboard
            redirect('yearselection/index');
          } else {
            // Gebruikersnaam is correct, maar wachtwoord is incorrect
            $data['error'] = 'Wachtwoord is incorrect';
            return $this->view('auth/login', $data);
          }
        } else {
          // Er zijn foutmeldingen, stuur de gebruiker terug naar de login pagina
          $this->view('auth/login', $data);
        }
      } else {
        // Gebruikersnaam is incorrect, stuur de gebruiker terug naar de login pagina
        $data['error'] = 'Gebruiker niet gevonden';
        return $this->view('auth/login', $data);
      }
    }
    $data = [
      'title' => 'Login',
      'username' => '',
      'password' => ''
    ];

    $this->view('auth/login', $data);
  }
}
