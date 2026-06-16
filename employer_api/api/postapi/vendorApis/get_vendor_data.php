<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'https://jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'https://jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Include necessary files
include "../../../includes/db-connection/db.php";
include "../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Use a try-catch block to handle exceptions gracefully
try {
    // Check for authorization header
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        throw new Exception("Authorization token not found", 401);
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $key = "J@ldiHire@321";

    // Decode the JWT token
    $jwt = JWT::decode($token, new Key($key, 'HS256'));
    $uId = $jwt->user_id;

    // Use a prepared statement to prevent SQL injection
    $query = "SELECT * FROM `vendors` WHERE `vendor_id` = ? AND `status` = 1";

    $stmt = $con->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare query: " . $con->error, 500);
    }

    $stmt->bind_param("s", $uId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: " . $stmt->error, 500);
    }

    $result = $stmt->get_result();

    // Fetch a single row as an associative array
    $userData = $result->fetch_assoc();

    // Check if any data was found
    if (!$userData) {
        throw new Exception("No vendor data found for the given ID", 404);
    }

    // Success response
    http_response_code(200);
    echo json_encode(['status' => true, 'data' => $userData]);
} catch (Exception $e) {
    // Determine the HTTP response code based on the exception code
    $statusCode = $e->getCode() > 0 ? $e->getCode() : 400;
    http_response_code($statusCode);
    echo json_encode([
        'status' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    // Ensure the database connection is closed
    if (isset($con)) {
        $con->close();
    }
}
