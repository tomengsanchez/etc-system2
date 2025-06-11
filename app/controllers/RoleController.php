<?php
class RoleController extends Controller {
    private $roleModel;

    public function __construct(){
        if(!isLoggedIn()){
            redirect('user/login');
        }

        // Protect this controller for admins only
        if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin'){
            flash('danger', 'Unauthorized access to that page');
            redirect('dashboard');
        }

        $this->roleModel = $this->model('Role');
    }

    public function index(){
        $roles = $this->roleModel->getRoles();
        $data = [
            'roles' => $roles
        ];
        $this->view('roles/index', $data);
    }

    public function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'name_err' => ''
            ];

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter role name';
            } elseif ($this->roleModel->findRoleByName($data['name'])){
                 $data['name_err'] = 'Role name is already taken';
            }

            if(empty($data['name_err'])){
                if($this->roleModel->addRole($data)){
                    flash('success', 'Role Added');
                    redirect('role');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('roles/create', $data);
            }
        } else {
            $data = [
                'name' => '',
                'name_err' => ''
            ];
            $this->view('roles/create', $data);
        }
    }

    public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'name_err' => ''
            ];

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter role name';
            }

            if(empty($data['name_err'])){
                if($this->roleModel->updateRole($data)){
                    flash('success', 'Role Updated');
                    redirect('role');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('roles/edit', $data);
            }
        } else {
            $role = $this->roleModel->getRoleById($id);
            if(!$role){
                redirect('roles');
            }
            $data = [
                'id' => $id,
                'name' => $role->name,
                'name_err' => ''
            ];
            $this->view('roles/edit', $data);
        }
    }
    
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $role = $this->roleModel->getRoleById($id);
            // Prevent deletion of core roles
            if(in_array($role->name, ['admin', 'student', 'teacher'])){
                 flash('danger', 'Cannot delete core application roles.');
                 redirect('role');
            }

            if($this->roleModel->deleteRole($id)){
                flash('success', 'Role Removed');
                redirect('role');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('role');
        }
    }
}
