<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    /**
     * Register User and return verification code
     * @param array $data Contains name, email, and hashed password
     * @return string|false Verification code on success, false on failure
     */
    public function register($data){
        // Generate a unique verification token
        $verification_code = bin2hex(random_bytes(16));

        // Insert new user with a default role_id for 'student' (ID 2)
        $this->db->query('INSERT INTO users (name, email, password, verification_code, role_id) VALUES (:name, :email, :password, :verification_code, :role_id)');
        // Bind values
        $this->db->bind(':name', $data['name']); // Use the correct 'name' key from the controller
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':verification_code', $verification_code);
        $this->db->bind(':role_id', 2); // Assign default role 'student' (ID=2) on registration

        // Execute
        if($this->db->execute()){
            // Return the verification code so it can be emailed to the user
            return $verification_code;
        } else {
            return false;
        }
    }

    /**
     * Login User
     * @param string $email
     * @param string $password
     * @return object|false User object on success, false on failure
     */
    public function login($email, $password){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
                return $row; // Return user object if password is correct
            } else {
                return false; // Return false if password is not correct
            }
        } else {
            return false; // Return false if no user found with that email
        }
    }

    /**
     * Find user by email
     * @param string $email
     * @return bool
     */
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        // Bind value
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        return $this->db->rowCount() > 0;
    }

    /**
     * Find user by verification code
     * @param string $token
     * @return object|false User object on success, false on failure
     */
    public function findUserByVerificationCode($token){
        $this->db->query('SELECT * FROM users WHERE verification_code = :token AND is_verified = 0');
        $this->db->bind(':token', $token);

        $row = $this->db->single();

        return ($this->db->rowCount() > 0) ? $row : false;
    }

    /**
     * Mark a user's email as verified
     * @param string $token
     * @return bool
     */
    public function verifyUser($token){
        $this->db->query('UPDATE users SET is_verified = 1, verification_code = NULL WHERE verification_code = :token');
        $this->db->bind(':token', $token);

        // Execute
        return $this->db->execute();
    }

    /**
     * Get User by ID
     * @param int $id
     * @return object
     */
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        // Bind value
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }
}
