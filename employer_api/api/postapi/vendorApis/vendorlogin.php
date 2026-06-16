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

// Include JWT library at the top to ensure it's available for all cases
require_once "../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    // Include database connection
    include "../../../includes/db-connection/db.php";

    // Check if database connection is successful
    if (!$con) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    // Get input data
    $postdata = file_get_contents("php://input");
    if (empty($postdata)) {
        throw new Exception("No input data received");
    }

    $data = json_decode($postdata);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON data: " . json_last_error_msg());
    }

    // Validate required fields
    if (!isset($data->spoc_email)) {
        throw new Exception("spoc_email is required");
    }
    if (!isset($data->password)) {
        throw new Exception("password is required");
    }

    // Sanitize input
    $spoc_email = mysqli_real_escape_string($con, trim($data->spoc_email));
    $password = $data->password;

    // Query database using prepared statement (better security)
    $stmt = mysqli_prepare($con, "SELECT vendor_id, password FROM vendors WHERE spoc_email = ? AND status = 1");
    mysqli_stmt_bind_param($stmt, "s", $spoc_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($con));
    }

    $count = mysqli_num_rows($result);
    if ($count == 1) {
        $fetchUser = mysqli_fetch_assoc($result);
        $userId = $fetchUser['vendor_id'];
        $hash = $fetchUser['password'];

        if (!password_verify($password, $hash)) {
            http_response_code(401); // Unauthorized
            echo json_encode([
                'status' => false,
                'message' => 'Incorrect Password'
            ]);
            exit();
        }

        // JWT Configuration
        $key = "J@ldiHire@321";
        $role = "Vendor";
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();

        $payload = [
            "user_id" => $userId,
            "role" => $role,
            'iat' => $issuedAt->getTimestamp(),
            'nbf' => $issuedAt->getTimestamp(),
            'exp' => $expire,
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        // Successful response
        http_response_code(200);
        echo json_encode([
            'status' => true,
            'message' => 'Vendor logged in successfully',
            'JwtToken' => $jwt,
            'user_id' => $userId
        ]);
    } else {
        http_response_code(404); // Not found
        echo json_encode([
            'status' => false,
            'message' => 'User not found or inactive'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Server error',
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}

// Close database connection if it exists
if (isset($con)) {
    mysqli_close($con);
}
