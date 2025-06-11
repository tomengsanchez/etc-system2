<?php
// app/controllers/ApiController.php

// Load necessary helpers and models
require_once '../app/helpers/jwt_helper.php';

class ApiController extends Controller {
    private $userModel;
    private $roleModel;

    public function __construct(){
        $this->userModel = $this->model('User');
        $this->roleModel = $this->model('Role');
    }

    public function login(){
        header('Content-Type: application/json');
        
        // Get the raw POST data
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        // Check for required data
        if (!isset($data->email) || !isset($data->password)) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Email and password are required.']);
            return;
        }

        // Check for user
        if($this->userModel->findUserByEmail($data->email)){
            // User found, check login
            $loggedInUser = $this->userModel->login($data->email, $data->password);

            if($loggedInUser){
                // Fetch user's role
                $role = $this->roleModel->getRoleByUserId($loggedInUser->id);
                
                // Check if the user is an admin before generating a token
                if ($role && isset($role->role_name) && $role->role_name == 'admin') {
                    // User is an admin, generate JWT
                    $jwt = create_jwt(['user_id' => $loggedInUser->id, 'role' => $role->role_name]);
                    http_response_code(200); // OK
                    echo json_encode(['token' => $jwt]);
                } else {
                    // User is not an admin, access denied
                    http_response_code(403); // Forbidden
                    echo json_encode(['message' => 'Access Denied. Administrator privileges required.']);
                }
            } else {
                // Password incorrect
                http_response_code(401); // Unauthorized
                echo json_encode(['message' => 'Password incorrect.']);
            }
        } else {
            // No user found
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'User not found.']);
        }
    }
}
