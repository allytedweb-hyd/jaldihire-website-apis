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
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);

TokenVerify($token);
if (isset($_GET['appId']) && !empty($_GET['appId'])) {

    $applicant_id = $_GET['appId'];

    $get_applicants_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team a,applicant_recruiter_assigns b WHERE a.allyted_member_id = b.recruiter_assigned_id AND b.applicant_assigned_id='" . $applicant_id . "'"));

    $status = ['status' => true, 'data' => $get_applicants_data];
} else {
    $status = ['status' => false, 'error' => "Missing Parameters"];
}
echo json_encode($status);
