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
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
include "../../includes/db-connection/db.php";
include "../lib/jwt.php";

// Accesing the Headers
$headers = apache_request_headers();

// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $q = "SELECT * FROM tbl_add_employer WHERE prefix_code='" . $data->prefixCode . "'";
    $checkPrefix_code = mysqli_query($con, $q);
    if (mysqli_num_rows($checkPrefix_code) > 0) {
        $status = ['status' => false];
    } else {
        $status = ['status' => true];
    }
} else {
    $status = ['status' => false, 'message' => 'Something Went Wrong'];
}

echo json_encode($status);
