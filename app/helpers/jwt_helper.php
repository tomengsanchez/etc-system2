<?php
// Correctly locate the vendor autoload file from the project root.
// APPROOT is defined in config.php and points to the 'app' directory.
require_once dirname(APPROOT) . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JWT Helper
 *
 * This helper provides functions for creating and validating JSON Web Tokens.
 * It relies on the firebase/php-jwt library.
 */
class JWT_Helper {

    /**
     * Generates a JWT token.
     *
     * @param array $data The payload to include in the token.
     * @return string The generated JWT.
     */
    public static function create_jwt($data) {
        $payload = array(
            "iss" => APPROOT, // Issuer
            "aud" => APPROOT, // Audience
            "iat" => time(), // Issued at
            "nbf" => time(), // Not before
            "exp" => time() + JWT_EXP, // Expiration time
            "data" => $data // User data
        );

        return JWT::encode($payload, JWT_SECRET, 'HS256');
    }

    /**
     * Validates a JWT token.
     *
     * @param string $jwt The token to validate.
     * @return object The decoded payload if the token is valid.
     * @throws Exception if the token is invalid.
     */
    public static function validate_jwt($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key(JWT_SECRET, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            throw new Exception("Invalid Token: " . $e->getMessage());
        }
    }
}
