<?php
class UserController extends Controller {
    private $userModel;
    private $roleModel;

    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function register(){
        // Check for POST request
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST data to prevent XSS
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Init data
            $data =[
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } elseif($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Email is already taken';
            }

            // Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password';
            } elseif($data['password'] != $data['confirm_password']){
                $data['confirm_password_err'] = 'Passwords do not match';
            }

            // Make sure all errors are empty
            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if($verification_code = $this->userModel->register($data)){
                    // Send verification email
                    sendVerificationEmail($data['email'], $verification_code);
                    // Redirect to login page
                    redirect('user/login');
                } else {
                    die('Something went wrong with user registration.');
                }
                
            } else {
                // Load view with validation errors
                $this->view('user/register', $data);
            }

        } else {
            // Load form
            $data =[
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('user/register', $data);
        }
    }

    public function login(){
        // Check for POST request
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data =[
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',      
            ];

            // Validate Email & Password
            if(empty($data['email'])) $data['email_err'] = 'Please enter email';
            if(empty($data['password'])) $data['password_err'] = 'Please enter password';

            // Check for user by email
            if(!$this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    // *** EMAIL VERIFICATION CHECK ***
                    // Defensively check if the property exists. Treat as not verified if missing.
                    if (isset($loggedInUser->is_verified) && $loggedInUser->is_verified == 1) {
                        // Create Session if verified
                        $this->createUserSession($loggedInUser);
                    } else {
                        // User not verified, show error and redirect
                        flash('login_fail', 'Your account is not verified. Please check your email for the verification link.', 'alert alert-danger');
                        redirect('user/login');
                    }
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('user/login', $data);
                }
            } else {
                $this->view('user/login', $data);
            }
        } else {
            // Load form
            $data =[    
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',        
            ];
            $this->view('user/login', $data);
        }
    }

    /**
     * Handle email verification from the link clicked by the user
     * @param string $token The verification token from the URL
     */
    public function verify($token = ''){
        if(empty($token)){
            flash('verify_fail', 'Verification token is missing.', 'alert alert-danger');
            redirect('user/login');
            return;
        }

        // Check if a user exists with this token
        if($this->userModel->findUserByVerificationCode($token)){
            // User found, attempt to verify
            if($this->userModel->verifyUser($token)){
                flash('verify_success', 'Your email has been successfully verified. You can now log in.');
                redirect('user/login');
            } else {
                flash('verify_fail', 'Something went wrong during verification. Please try again.', 'alert alert-danger');
                redirect('user/login');
            }
        } else {
            // Token is invalid or account is already verified
            flash('verify_fail', 'Invalid verification token or your account is already verified.', 'alert alert-danger');
            redirect('user/login');
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name; // Use 'name' to match the database column
        
        // Fetch role and set it in session
        $this->roleModel = $this->model('Role');
        $role = $this->roleModel->getRoleByUserId($user->id);

        // Check if a role was found and has the required properties
        if ($role && isset($role->role_name) && isset($role->id)) {
            $_SESSION['user_role'] = $role->role_name;
            $_SESSION['user_role_id'] = $role->id;
        } else {
            // Assign a default role if none is found or if data is incomplete
            $_SESSION['user_role'] = 'student'; 
            $_SESSION['user_role_id'] = 2; // Default to student role ID
        }

        redirect('dashboard');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_role_id']);
        session_destroy();
        redirect('user/login');
    }
}
