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

session_start();

include "../../includes/db-connection/db.php";
include '../libs/jwt.php';

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


$response = TokenVerify($token);


$userId = $response['userId'];
$role = $response['role'];

if ($role === 'admin') {
    $updateQuery = "UPDATE tbl_add_employer SET is_logged_in = 0,login_time='0000-00-00 00:00:00' WHERE employer_auto_create_id ='" . $userId . "'";
} else if ($role === 'team') {
    $updateQuery = "UPDATE tbl_add_team SET is_logged_in = 0,login_time='0000-00-00 00:00:00' WHERE auto_create_id = '" . $userId . "'";
} else if ($role === 'RM') {
    $updateQuery = "UPDATE tbl_resource_managers SET is_logged_in = 0,login_time='0000-00-00 00:00:00' WHERE RM_auto_create_id ='" . $userId . "'";
} else {
    exit(json_encode(['status' => false, 'message' => 'Invalid role']));
}

$stmt = $con->prepare($updateQuery);
$stmt->execute();

session_destroy();

echo json_encode(['status' => true, 'message' => 'User logged out successfully']);
exit();
