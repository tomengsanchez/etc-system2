<?php
class Role
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getRoles()
    {
        $this->db->query('SELECT * FROM roles ORDER BY name ASC');

        $results = $this->db->resultSet();

        return $results;
    }

    public function getRoleById($id)
    {
        $this->db->query('SELECT * FROM roles WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    /**
     * Add a new role to the database.
     * @param array $data ['name' => 'RoleName']
     * @return bool True on success, false on failure.
     */
    public function addRole($data)
    {
        $this->db->query('INSERT INTO roles (name) VALUES (:name)');
        // Bind values
        $this->db->bind(':name', $data['name']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update an existing role in the database.
     * @param array $data ['id' => 1, 'name' => 'UpdatedName']
     * @return bool True on success, false on failure.
     */
    public function updateRole($data)
    {
        $this->db->query('UPDATE roles SET name = :name WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a role from the database.
     * @param int $id The ID of the role to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteRole($id)
    {
        $this->db->query('DELETE FROM roles WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
