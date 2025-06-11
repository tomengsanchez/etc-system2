<?php
class ApiController extends Controller
{
    private $userModel; // Property is now explicitly declared

    public function __construct()
    {
        // Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

        $this->userModel = $this->model('User');
    }

    public function login()
    {
        // Load JWT helper only when needed
        require_once '../app/helpers/jwt_helper.php';

        // Get raw posted data
        $data = json_decode(file_get_contents("php://input"));

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data->email) || !isset($data->password)) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Invalid input.']);
            return;
        }

        $loggedInUser = $this->userModel->login($data->email, $data->password);

        if ($loggedInUser) {
            // User authenticated, create JWT
            $user_data = [
                'id' => $loggedInUser->id,
                'name' => $loggedInUser->name,
                'email' => $loggedInUser->email,
                'role' => $loggedInUser->role_name // Include user role
            ];

            $jwt = JWT_Helper::create_jwt($user_data);

            http_response_code(200);
            echo json_encode([
                'message' => 'Successful login.',
                'token' => $jwt
            ]);
        } else {
            // Login failed
            http_response_code(401);
            echo json_encode(['message' => 'Login failed. Incorrect email or password.']);
        }
    }

    /**
     * Dispatches requests for /api/roles to the RoleApiController
     */
    public function roles()
    {
        // Manually load and instantiate the RoleApiController
        require_once '../app/controllers/RoleApiController.php';
        $roleApiController = new RoleApiController();
        // Call its main entry point method
        $roleApiController->index();
    }
}
