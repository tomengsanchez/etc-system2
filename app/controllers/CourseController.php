<?php
class CourseController extends Controller {
    private $courseModel;
    private $userModel;

    public function __construct(){
        if(!isLoggedIn()){
            redirect('user/login');
        }

        $this->courseModel = $this->model('Course');
        $this->userModel = $this->model('User');
    }

    public function index(){
        $courses = $this->courseModel->getAllCourses();
        $data = [
            'courses' => $courses,
            'title' => 'Courses' // Added missing title
        ];
        $this->view('course/index', $data);
    }

    public function create(){
        if(!isAdmin() && !isTeacher()){
            flash('danger', 'Unauthorized access to that page');
            redirect('course');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'user_id' => $_SESSION['user_id'],
                'name_err' => '',
                'description_err' => ''
            ];

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter course name';
            }
            if(empty($data['description'])){
                $data['description_err'] = 'Please enter a description';
            }

            if(empty($data['name_err']) && empty($data['description_err'])){
                if($this->courseModel->addCourse($data)){
                    flash('success', 'Course Added');
                    redirect('course');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('course/create', $data);
            }
        } else {
            $data = [
                'name' => '',
                'description' => '',
                'name_err' => '',
                'description_err' => ''
            ];
            $this->view('course/create', $data);
        }
    }

    public function edit($id){
        if(!isAdmin() && !isTeacher()){
            flash('danger', 'Unauthorized access to that page');
            redirect('course');
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'description_err' => ''
            ];

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter course name';
            }
             if(empty($data['description'])){
                $data['description_err'] = 'Please enter a description';
            }

            if(empty($data['name_err']) && empty($data['description_err'])){
                if($this->courseModel->updateCourse($data)){
                    flash('success', 'Course Updated');
                    redirect('course');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('course/edit', $data);
            }
        } else {
            $course = $this->courseModel->getCourseById($id);
            if(!$course){
                redirect('course');
            }
            $data = [
                'id' => $id,
                'name' => $course->name,
                'description' => $course->description
            ];
            $this->view('course/edit', $data);
        }
    }

    public function delete($id){
        if(!isAdmin() && !isTeacher()){
            flash('danger', 'Unauthorized access to that page');
            redirect('course');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->courseModel->deleteCourse($id)){
                flash('success', 'Course Removed');
                redirect('course');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('course');
        }
    }
}
