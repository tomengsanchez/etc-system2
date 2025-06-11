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

   // Email Configuration - ** FILL THIS OUT **
  define('SMTP_HOST', 'mail.ecosyscorp.ph'); // Your SMTP server address
  define('SMTP_USERNAME', 'tomeng@ecosyscorp.ph'); // Your SMTP username (full email address)
  define('SMTP_PASSWORD', 'Tomengmengdead#67'); // Your SMTP password or app-specific password
  define('SMTP_PORT', 465); // Port for TLS (or 465 for SSL)
  define('SMTP_SECURE', 'ssl'); // Encryption method: 'tls' or 'ssl'
  define('SMTP_FROM_EMAIL', 'no-reply@ecosyscorp.ph'); // The "From" email address
  define('SMTP_FROM_NAME', 'Ecosys Training Center'); // The "From" name


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


    // JWT Secret Key
  define('JWT_SECRET', 'TEAMPOGI'); // <-- IMPORTANT: Change this to a long, random string
  // JWT Expiration Time (in seconds)
  define('JWT_EXP', 3600); // 1 hour