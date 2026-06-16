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


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include "../../../includes/db-connection/db.php";
require_once "../../vendor/autoload.php";

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


$key = "J@ldiHire@321";

try {
    $jwt = JWT::decode($token, new Key($key, 'HS256'));
} catch (\Exception $e) {
    echo json_encode(["status" => false, "error" => "Token has Expired. Please login again."]);
    exit();
}

// Get the employer_id from the request
$employerId = $_GET['employer_id'] ?? null;

if (!$employerId) {
    echo json_encode(["status" => false, "message" => "Employer ID is required."]);
    exit();
}

// Prepare and execute the query
$query = "SELECT company_name FROM tbl_add_employer WHERE employer_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $employerId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Employer found, return the name
    echo json_encode(["status" => true, "employer_name" => $row['company_name']]);
} else {
    // Employer not found
    echo json_encode(["status" => false, "message" => "Employer not found."]);
}

mysqli_close($con);
