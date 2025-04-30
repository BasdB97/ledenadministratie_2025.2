<?php
class Core
{
  protected $currentController = 'Auth';
  protected $currentMethod = 'login';
  protected $params = [];

  public function __construct()
  {
    $url = $this->getUrl();

    if (isset($url[0])) {
      $controllerFile = '../app/controllers/' . ucwords($url[0]) . 'Controller.php';
      if (file_exists($controllerFile)) {
        $this->currentController = ucwords($url[0]);
        unset($url[0]);
      }
    }

    $controllerPath = '../app/controllers/' . $this->currentController . 'Controller.php';
    require_once $controllerPath;
    $this->currentController = $this->currentController . 'Controller';
    $this->currentController = new $this->currentController;

    if (isset($url[1])) {
      if (method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        unset($url[1]);
      } // else {
      // $this->currentMethod = 'login';
      // }
    }

    $this->params = $url ? array_values($url) : [];
    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
  }

  public function getUrl()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
  }
}
