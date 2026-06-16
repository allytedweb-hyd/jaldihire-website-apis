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

include "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include "../../includes/db-connection/db.php";


$json = file_get_contents('php://input');
$data = json_decode($json);
$headers = apache_request_headers();

if (isset($headers['Authorization'])) {
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $key = "J@ldiHire@321";
    try {
        $jwt = JWT::decode($token, new Key($key, 'HS256'));
    } catch (\Exception $e) {
        echo json_encode(["status" => false, "error" => "Token has Expired Please Login Again"]);
        exit();
    }
    $data = [];
    $userData = [];

    $uId = $jwt->user_id;
    $getData = mysqli_query($con, "SELECT * FROM tbl_login WHERE login_id='" . $uId . "' and login_status=1");

    while ($uData = mysqli_fetch_array($getData)) {
        $userData = [$uData];
    }

    $data = ['status' => true, 'data' => $userData];
    echo json_encode(['status' => true, 'data' => $userData]);
} else {
    $data = ['status' => false, 'error' => 'Token Not Found'];
    echo json_encode($data);
}
