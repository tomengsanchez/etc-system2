<?php
class Role {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getRoles(){
      $this->db->query('SELECT * FROM roles');

      $results = $this->db->resultSet();

      return $results;
    }
    
    public function getRoleById($id){
        $this->db->query('SELECT * FROM roles WHERE id = :id');
        $this->db->bind(':id', $id);
  
        $row = $this->db->single();
  
        return $row;
    }

    /**
     * Get a user's role by their user ID.
     * Joins the users and roles tables.
     * @param int $user_id The ID of the user.
     * @return object|false The role object on success, false on failure.
     */
    public function getRoleByUserId($user_id){
        $this->db->query('SELECT r.* FROM roles r JOIN users u ON r.id = u.role_id WHERE u.id = :user_id');
        $this->db->bind(':user_id', $user_id);

        $row = $this->db->single();

        return $row;
    }

    public function addRole($data){
        $this->db->query('INSERT INTO roles (role_name) VALUES (:role_name)');
        // Bind values
        $this->db->bind(':role_name', $data['role_name']);
  
        // Execute
        if($this->db->execute()){
          return true;
        } else {
          return false;
        }
    }

    public function updateRole($data){
        $this->db->query('UPDATE roles SET role_name = :role_name WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':role_name', $data['role_name']);
  
        // Execute
        if($this->db->execute()){
          return true;
        } else {
          return false;
        }
    }

    public function deleteRole($id){
        $this->db->query('DELETE FROM roles WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $id);
  
        // Execute
        if($this->db->execute()){
          return true;
        } else {
          return false;
        }
    }
    
    public function getRoleByRoleName($roleName){
        $this->db->query('SELECT * FROM roles WHERE role_name = :role_name');
        $this->db->bind(':role_name', $roleName);
  
        $row = $this->db->single();
  
        return $row;
    }
}
