<?php

/**
 * API Controller
 *
 * Handles all API requests.
 */
class ApiController extends Controller
{
    private $userModel;

    public function __construct()
    {
        // Set the content type to JSON for all responses from this controller
        header('Content-Type: application/json');
        
        // Load the User model
        $this->userModel = $this->model('User');
    }

    /**
     * API Login Endpoint
     *
     * Handles user login via API. Expects a POST request with
     * a JSON body containing 'email' and 'password'.
     */
    public function login()
    {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['error' => 'Invalid request method'], 405); // Method Not Allowed
            return;
        }

        // Get the raw POST data
        $json = file_get_contents('php://input');
        // Decode the JSON data into an associative array
        $data = json_decode($json, true);

        // --- Basic Validation ---
        if (!isset($data['email']) || !isset($data['password'])) {
            json_response(['error' => 'Email and password are required'], 400); // Bad Request
            return;
        }

        // Sanitize the input
        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($data['password']);

        if (empty($email) || empty($password)) {
            json_response(['error' => 'Email and password cannot be empty'], 400);
            return;
        }

        // --- Authenticate User ---
        $loggedInUser = $this->userModel->login($email, $password);

        if ($loggedInUser) {
            // On successful login, return user data (excluding password)
            $userData = [
                'id' => $loggedInUser->id,
                'name' => $loggedInUser->name,
                'email' => $loggedInUser->email,
                'created_at' => $loggedInUser->created_at
            ];
            json_response(['message' => 'Login successful', 'user' => $userData], 200);
        } else {
            // On failed login
            json_response(['error' => 'Invalid credentials'], 401); // Unauthorized
        }
    }

    /**
     * Default method for handling unknown API endpoints.
     */
    public function index()
    {
        json_response(['error' => 'API endpoint not found'], 404);
    }
}
