<?php
  /**
   * Dashboard Model
   *
   * This model is responsible for fetching all data required for the
   * dashboard from the database.
   */
  class Dashboard
  {
      private $db;

      public function __construct()
      {
          // Instantiate the database connection.
          $this->db = new Database();
      }

      /**
       * Gets the total count of all registered users.
       *
       * @return int The total number of users.
       */
      public function getUserCount()
      {
          // A simple and efficient query to count rows.
          $this->db->query('SELECT COUNT(id) as count FROM users');
          
          // Fetch the single result.
          $row = $this->db->single();
          
          // Return the count.
          return $row->count;
      }

      /**
       * Example for getting the total number of courses.
       * * In the future, this would query the 'courses' table or EAV tables.
       * For now, it demonstrates how you could extend the model.
       */
      /*
      public function getCourseCount()
      {
          $this->db->query('SELECT COUNT(id) as count FROM courses'); // Assuming a 'courses' table
          $row = $this->db->single();
          return $row->count;
      }
      */
  }
