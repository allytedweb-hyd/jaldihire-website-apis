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
if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    $recruiterId = $_GET['recruiter_id'];
    $data = [];
    $getApplicants = mysqli_query($con, "SELECT * FROM applicant_recruiter_assigns T1, tbl_applicant_add T2 WHERE T1.recruiter_assigned_id='" . $recruiterId . "' AND T2.applicant_id = T1.applicant_assigned_id && T2.status=1 && T1.status=1");

    while ($row = $getApplicants->fetch_assoc()) {
        $data[] = $row;
    }

    $status = ['status' => true, 'data' => $data];
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);
