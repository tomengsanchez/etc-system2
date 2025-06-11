<?php
  // DB Params
  define('DB_HOST', 'localhost');
  define('DB_USER', 'ms1user');
  define('DB_PASS', 'mainsystem#67');
  define('DB_NAME', 'enroll');

  // App Root - dynamically gets the absolute path to the app folder
  define('APPROOT', dirname(dirname(__FILE__)));
  // URL Root
  define('URLROOT', 'https://ithelp.ecosyscorp.ph/etc-system');
  // Site Name
  define('SITENAME', 'Ecosys Training Center');

  // Load Helpers using the APPROOT constant for a reliable path
  require_once APPROOT . '/helpers/session_helper.php';
    require_once APPROOT . '/helpers/role_helper.php'; // <-- This line fixes the error
  // I am assuming url_helper.php is also in the helpers directory
  // If it's not, you may need to adjust or remove this line.
  if (file_exists(APPROOT . '/helpers/url_helper.php')) {
    require_once APPROOT . '/helpers/url_helper.php';
  }
  // Load API helper
  if (file_exists(APPROOT . '/helpers/api_helper.php')) {
    require_once APPROOT . '/helpers/api_helper.php';
  }


  // Generate CSRF token for the session
  generate_csrf_token();

  // Autoload Core Libraries
  spl_autoload_register(function($className){
    require_once APPROOT . '/core/' . $className . '.php';
  });
