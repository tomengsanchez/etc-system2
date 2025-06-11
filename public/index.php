<?php



/**
 * Set the timezone for all date/time functions.
 */
date_default_timezone_set('Asia/Manila');

/**
 * The full path to the error log file.
 * dirname(__DIR__) gets the project root directory (one level up from /public).
 */
define('ERROR_LOG_FILE', dirname(__DIR__) . '/error_log');

/**
 * Configure PHP error reporting.
 *
 * E_ALL: Report all PHP errors.
 * display_errors (0): Do not display errors to the user. This is a crucial
 * security measure for a production environment.
 * log_errors (1): Log errors to the location specified by `error_log`.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', ERROR_LOG_FILE);

/**
 * Custom Exception Handler.
 * This function is called automatically whenever an uncaught exception occurs.
 * It formats the exception details and writes them to our log file.
 *
 * @param Throwable $exception The exception that was thrown.
 */
function handleException(Throwable $exception): void
{
    $logEntry = sprintf(
        "[%s] Uncaught Exception: %s in %s:%d\nStack trace:\n%s\n---\n",
        date('d-M-Y H:i:s e'),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );

    error_log($logEntry, 3, ERROR_LOG_FILE);
    
    // You can also show a generic error page to the user here
    // http_response_code(500);
    // include(dirname(__DIR__) . '/app/views/inc/500.php'); // Example
}

/**
 * Custom Error Handler.
 * This function is called for non-fatal errors (like warnings and notices),
 * converting them into ErrorException objects so they can be logged consistently.
 *
 * @param int    $severity The level of the error raised.
 * @param string $message  The error message.
 * @param string $file     The filename that the error was raised in.
 * @param int    $line     The line number the error was raised at.
 * @throws ErrorException
 */
function handleError(int $severity, string $message, string $file, int $line): void
{
    // This error code is not included in error_reporting
    if (!(error_reporting() & $severity)) {
        return;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
}

/**
 * Register our custom handlers.
 * From this point forward, all exceptions and errors will be processed
 * by the functions defined above.
 */
set_exception_handler('handleException');
set_error_handler('handleError');



// Load Config
require_once __DIR__ . '/../app/config/config.php';

// Load Helpers
require_once __DIR__ . '/../app/helpers/session_helper.php';

// Load Core Libraries
require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Database.php';


// Bootstrap the application by instantiating the App class.
$app = new App;