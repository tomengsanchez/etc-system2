<?php
  /**
   * Dashboard Controller
   *
   * This controller handles the logic for the main dashboard, which is
   * visible to logged-in users.
   */
  class DashboardController extends Controller
  {
      private $dashboardModel;

      public function __construct()
      {
          // 1. A crucial security check: if the user is not logged in,
          //    redirect them to the login page immediately.
          if (!isLoggedIn()) {
              redirect('user/login');
          }

          // 2. Load the corresponding model for this controller.
          $this->dashboardModel = $this->model('Dashboard');
      }

      /**
       * The main dashboard view.
       *
       * It fetches summary data from the model and displays it.
       */
      public function index()
      {
          // Get the total number of registered users.
          $userCount = $this->dashboardModel->getUserCount();

          // You can easily add more data here in the future. For example:
          // $courseCount = $this->dashboardModel->getCourseCount();
          // $enrollmentCount = $this->dashboardModel->getEnrollmentCount();

          // Prepare the data to be passed to the view.
          $data = [
              'title' => 'Dashboard',
              'user_count' => $userCount
              // 'course_count' => $courseCount,
              // 'enrollment_count' => $enrollmentCount,
          ];

          // Load the view file and pass the data to it.
          $this->view('dashboard/index', $data);
      }
  }
