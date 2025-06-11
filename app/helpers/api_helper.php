<?php

/**
 * Sends a JSON response.
 *
 * @param mixed $data The data to encode as JSON.
 * @param int $statusCode The HTTP status code to send.
 */
function json_response($data, $statusCode = 200)
{
    // Set the HTTP response code
    http_response_code($statusCode);
    // Set the content type header
    header('Content-Type: application/json');
    // Output the JSON encoded data
    echo json_encode($data);
    // Terminate the script
    exit();
}
