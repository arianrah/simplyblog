<?php
// main app core class
// creates URL & loads core controller
// URL formate - /controller/method/params
error_reporting(E_ALL ^ E_WARNING);

class Core
{
  protected $currentController = 'Pages';
  protected $currentMethod = 'index';
  protected $params = [];

  public function __construct()
  {

    $url = $this->getUrl();

    // Look in controllers for first value
    if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {

      // If exists, set as controller
      $this->currentController = ucwords($url[0]);

      // Unset 0 Index
      unset($url[0]);
    }
    // require controller
    require_once '../app/controllers/' . $this->currentController . '.php';

    // instantiate controller
    $this->currentController = new $this->currentController;

    //check url attri
    if (isset($url[1])) {

      // check if method exists
      if (method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        unset($url[1]);
      }
    }
    //get params
    $this->params = $url ? array_values($url) : [];

    // call cb w/ array params
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
