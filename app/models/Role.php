<?php
  class Role {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    /**
     * Get all roles from the database
     *
     * @return array
     */
    public function getRoles(){
      $this->db->query("SELECT * FROM roles ORDER BY name ASC");
      $results = $this->db->resultSet();
      return $results;
    }

    /**
     * Get a role by its ID
     *
     * @param int $id
     * @return object
     */
    public function getRoleById($id){
        $this->db->query("SELECT * FROM roles WHERE id = :id");
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }
  }
