<?php
  /**
   * Redirect to a specific page within the application.
   *
   * @param string $page The page to redirect to (e.g., 'users/login').
   */
  function redirect($page){
    // Redirect using the URLROOT defined in config.php
    header('location: ' . URLROOT . '/' . $page);
    exit(); // Terminate script execution after a redirect
  }