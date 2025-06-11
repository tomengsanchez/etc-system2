<?php
  // Start session
  session_start();

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

  function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
      return true;
    } else {
      return false;
    }
  }

  // --- CSRF Token Functions ---

  /**
   * Generates a CSRF token if one doesn't already exist for the session.
   */
  function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        // Use bin2hex and random_bytes for a cryptographically secure token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  }

  /**
   * Returns the current CSRF token.
   *
   * @return string The CSRF token.
   */
  function get_csrf_token() {
    return $_SESSION['csrf_token'];
  }

  /**
   * Validates the submitted CSRF token against the one in the session.
   *
   * @param string $token The token submitted with the form.
   * @return bool True if the token is valid, false otherwise.
   */
  function validate_csrf_token($token) {
    // Use hash_equals for a timing-attack-safe comparison
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    error_log('Invalid CSRF token received.');
    return false;
  }
