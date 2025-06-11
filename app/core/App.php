<?php

/**
 * Main App Class (Router)
 *
 * This class is responsible for parsing the URL and loading the
 * correct controller and method, along with any parameters.
 */
class App
{
    // Default controller, method, and parameters
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // --- API Route Handling ---
        // Check if the first part of the URL is 'api'.
        if (isset($url[0]) && $url[0] == 'api') {
            // Shift 'api' off the URL array
            array_shift($url);

            // Set controller to ApiController
            $this->controller = 'ApiController';
            require_once '../app/controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;

            // Check for the method in the URL (e.g., /api/login)
            if (isset($url[0])) {
                if (method_exists($this->controller, $url[0])) {
                    $this->method = $url[0];
                    unset($url[0]);
                }
            }
             // Any remaining parts of the URL are treated as parameters.
            $this->params = $url ? array_values($url) : [];

            // Call the API controller's method with the parameters.
            call_user_func_array([$this->controller, $this->method], $this->params);
            return; // Stop processing for API routes
        }

        // --- Controller Handling (Web) ---
        // Look for a controller file that matches the first part of the URL.
        // We use ucfirst() to ensure the first letter of the class name is uppercase.
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        } else {
            // Default to a generic home controller if nothing is specified
            $this->controller = 'HomeController';
        }

        // Require the controller file.
        require_once '../app/controllers/' . $this->controller . '.php';

        // Instantiate the controller class.
        $this->controller = new $this->controller;


        // --- Method Handling (Web) ---
        // Check if a method is specified in the second part of the URL.
        if (isset($url[1])) {
            // Check if the method exists within the controller class.
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // --- Parameters Handling (Web) ---
        // Any remaining parts of the URL are treated as parameters.
        $this->params = $url ? array_values($url) : [];

        // Call the controller's method with the parameters.
        // This is the final step that executes the application logic for the request.
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parse the URL from the 'url' GET parameter.
     *
     * @return array The URL parts as an array.
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            // Trim trailing slash, sanitize, and explode the URL into an array.
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
