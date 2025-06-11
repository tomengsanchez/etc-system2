<?php
// Start session only if one isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Flash message helper
// EXAMPLE - flash('register_success', 'You are now registered');
// DISPLAY IN VIEW - echo flash('register_success');
function flash($name = '', $message = '', $class = 'alert alert-success'){
  if(!empty($name)){
    if(!empty($message) && empty($_SESSION[$name])){
      if(!empty($_SESSION[$name])){
        unset($_SESSION[$name]);
      }

      if(!empty($_SESSION[$name. '_class'])){
        unset($_SESSION[$name. '_class']);
      }

      $_SESSION[$name] = $message;
      $_SESSION[$name. '_class'] = $class;
    } elseif(empty($message) && !empty($_SESSION[$name])){
      $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
      echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
      unset($_SESSION[$name]);
      unset($_SESSION[$name. '_class']);
    }
  }
}

// CSRF Token Helper
function create_csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

// Alias function to fix undefined function error from other parts of the app
function generate_csrf_token(){
    return create_csrf_token();
}

function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Check if user is logged in
function isLoggedIn(){
    return isset($_SESSION['user_id']);
}
