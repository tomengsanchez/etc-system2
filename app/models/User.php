<?php
  class User {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // Register user
    public function register($data){
      // By default, the database sets role_id to 2 (student) for new users
      $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
      // Bind values
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':password', $data['password']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // Login User
    public function login($email, $password){
      // Join users and roles table to get the role name
      $this->db->query('SELECT users.*, roles.name as role_name FROM users 
                        INNER JOIN roles ON users.role_id = roles.id 
                        WHERE users.email = :email');
      $this->db->bind(':email', $email);

      $row = $this->db->single();

      // Check for user before verifying password
      if ($row) {
          $hashed_password = $row->password;
          if(password_verify($password, $hashed_password)){
              // Return user object with role name
              return $row;
          }
      }
      
      return false;
    }

    // Find user by email
    public function findUserByEmail($email){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      // Bind value
      $this->db->bind(':email', $email);

      $row = $this->db->single();

      // Check row
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    }

     // Find user by id
     public function getUserById($id){
      // Join users and roles table to get the role name
      $this->db->query('SELECT users.*, roles.name as role_name FROM users
                        INNER JOIN roles ON users.role_id = roles.id
                        WHERE users.id = :id');
      // Bind value
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }
  }
