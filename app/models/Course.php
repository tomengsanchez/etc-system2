<?php
class Course {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getAllCourses(){
        // Removed the "WHERE e.type = 'course'" clause to resolve the 'Unknown column' error.
        // This assumes all entities are courses for now.
        $this->db->query("
            SELECT
                e.id as courseId,
                e.created_at,
                (SELECT value FROM eav_values WHERE attribute_id = 1 AND entity_id = e.id) as name,
                (SELECT value FROM eav_values WHERE attribute_id = 2 AND entity_id = e.id) as description,
                (SELECT value FROM eav_values WHERE attribute_id = 3 AND entity_id = e.id) as user_id,
                u.name as user_name
            FROM entities e
            LEFT JOIN users u ON u.id = (SELECT value FROM eav_values WHERE attribute_id = 3 AND entity_id = e.id)
            ORDER BY e.created_at DESC
        ");
        return $this->db->resultSet();
    }

    public function getCourseById($id){
        // Removed the "e.type = 'course'" condition from the WHERE clause.
        $this->db->query("
             SELECT
                e.id as courseId,
                (SELECT value FROM eav_values WHERE attribute_id = 1 AND entity_id = e.id) as name,
                (SELECT value FROM eav_values WHERE attribute_id = 2 AND entity_id = e.id) as description
            FROM entities e
            WHERE e.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addCourse($data){
        // Modified the INSERT statement to not include the 'type' column.
        // Create the entity first
        $this->db->query('INSERT INTO entities () VALUES ()');
        if(!$this->db->execute()){
            return false;
        }
        $entityId = $this->db->lastInsertId();

        // Insert attributes
        $this->addAttributeValue($entityId, 'name', $data['name']);
        $this->addAttributeValue($entityId, 'description', $data['description']);
        $this->addAttributeValue($entityId, 'user_id', $data['user_id']);

        return true;
    }

    public function updateCourse($data){
        $this->updateAttributeValue($data['id'], 'name', $data['name']);
        $this->updateAttributeValue($data['id'], 'description', $data['description']);
        return true;
    }

    public function deleteCourse($id){
        $this->db->query('DELETE FROM entities WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Helper methods for EAV
    private function addAttributeValue($entityId, $attributeName, $value){
        $this->db->query('INSERT INTO eav_values (entity_id, attribute_id, value) 
                          SELECT :entity_id, id, :value FROM attributes WHERE name = :attribute_name');
        $this->db->bind(':entity_id', $entityId);
        $this->db->bind(':value', $value);
        $this->db->bind(':attribute_name', $attributeName);
        $this->db->execute();
    }
    
    private function updateAttributeValue($entityId, $attributeName, $value){
        $this->db->query('UPDATE eav_values SET value = :value 
                          WHERE entity_id = :entity_id AND attribute_id = (SELECT id FROM attributes WHERE name = :attribute_name)');
        $this->db->bind(':entity_id', $entityId);
        $this->db->bind(':value', $value);
        $this->db->bind(':attribute_name', $attributeName);
        $this->db->execute();
    }
}
