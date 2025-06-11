<?php
/**
 * Role API Controller
 *
 * This controller handles all API requests related to user roles.
 * It uses JWT for authentication to ensure that only authorized users
 * can perform CRUD operations on roles.
 */
class RoleApiController extends Controller
{
    private $roleModel;

    public function __construct()
    {
        // Load the Role model
        $this->roleModel = $this->model('Role');
        // Set response header to JSON
        header('Content-Type: application/json');
        // Load JWT helper
        require_once '../app/helpers/jwt_helper.php';
    }

    /**
     * Handles all incoming requests and routes them to the appropriate method.
     */
    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Verify the JWT token before proceeding
        try {
            $this->verifyAuthToken();
        } catch (Exception $e) {
            http_response_code(401); // Unauthorized
            echo json_encode(['message' => 'Authentication Failed: ' . $e->getMessage()]);
            return;
        }


        switch ($method) {
            case 'GET':
                $this->getRoles();
                break;
            case 'POST':
                $this->createRole();
                break;
            case 'PUT':
                $this->updateRole();
                break;
            case 'DELETE':
                $this->deleteRole();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(['message' => 'Method not allowed']);
                break;
        }
    }

    /**
     * Verifies the Authorization token from the request headers.
     * @throws Exception if token is invalid or not provided.
     */
    private function verifyAuthToken()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            throw new Exception("Authorization header not found.");
        }

        $authHeader = $headers['Authorization'];
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if (!$jwt) {
            throw new Exception("JWT token not found in Bearer schema.");
        }

        $decoded = JWT_Helper::validate_jwt($jwt);
        
        // Check if the user is an admin
        if (!isset($decoded->data->role) || $decoded->data->role !== 'admin') {
            throw new Exception("Access denied. Admin privileges required.");
        }
    }

    /**
     * Fetches all roles.
     * HTTP GET /api/roles
     */
    private function getRoles()
    {
        $roles = $this->roleModel->getRoles();
        echo json_encode($roles);
    }

    /**
     * Creates a new role.
     * HTTP POST /api/roles
     */
    private function createRole()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data->name)) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Invalid input.']);
            return;
        }

        $roleData = ['name' => htmlspecialchars(strip_tags($data->name))];

        if ($this->roleModel->addRole($roleData)) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Role created successfully.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Failed to create role.']);
        }
    }

    /**
     * Updates an existing role.
     * HTTP PUT /api/roles/{id}
     */
    private function updateRole()
    {
        $data = json_decode(file_get_contents("php://input"));

        // Extract ID from URL, e.g., /api/roles/12
        $url_parts = explode('/', $_GET['url']);
        $id = end($url_parts);


        if (json_last_error() !== JSON_ERROR_NONE || !isset($data->name) || !is_numeric($id)) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Invalid input or role ID.']);
            return;
        }

        $roleData = [
            'id' => (int)$id,
            'name' => htmlspecialchars(strip_tags($data->name))
        ];

        if ($this->roleModel->updateRole($roleData)) {
            echo json_encode(['message' => 'Role updated successfully.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Failed to update role.']);
        }
    }

    /**
     * Deletes a role.
     * HTTP DELETE /api/roles/{id}
     */
    private function deleteRole()
    {
        // Extract ID from URL, e.g., /api/roles/12
        $url_parts = explode('/', $_GET['url']);
        $id = end($url_parts);

        if (!is_numeric($id)) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Invalid role ID.']);
            return;
        }

        if ($this->roleModel->deleteRole((int)$id)) {
            echo json_encode(['message' => 'Role deleted successfully.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Failed to delete role.']);
        }
    }
}
