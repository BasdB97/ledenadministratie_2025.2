<?php

// Load configuration
require_once '../app/config/config.php';

// Load libraries
require_once '../app/libraries/Database.php';

// Load helpers
require_once '../app/helpers/url_helper.php';
require_once '../app/helpers/session_helper.php';

// Autoload Core Classes
spl_autoload_register(function ($className) {
  require_once '../app/libraries/' . $className . '.php';
});

$init = new Core();
