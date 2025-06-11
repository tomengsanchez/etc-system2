<?php

/**
 * HomeController
 *
 * This is the default controller for the application.
 * It handles the landing page.
 */
class HomeController extends Controller
{
    /**
     * The index method is the default method called for this controller.
     * It loads the home view and passes data to it.
     */
    public function index()
    {
        // Prepare data to be sent to the view
        $data = [
            'title' => 'Welcome to Ecosys Training Center',
            'description' => 'This is the homepage for the enrollment system, built on a custom PHP MVC framework.'
        ];

        // Load the view and pass the data
        // The view method is available because this class extends the base Controller
        $this->view('home/index', $data);
    }
}
