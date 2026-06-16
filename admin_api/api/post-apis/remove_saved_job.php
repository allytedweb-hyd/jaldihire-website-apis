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
date_default_timezone_set("Asia/Calcutta");
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


TokenVerify($token);

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $jobId = $data->job_id;
    $applicantId = $data->applicant_id;
    $created_date = date('Y-m-d H:i:s');

    $removeJob = mysqli_query($con, "UPDATE tbl_saved_jobs SET status='0' WHERE job_id='" . $jobId . "' AND applicant_id='" . $applicantId . "' AND status=1");
    if ($removeJob) {
        $stat = ['status' => true, 'message' => 'Removed successfully'];
    } else {
        $stat = ['status' => false, 'message' => 'Failed! Please Try Again'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong! Try Again'];
}
echo json_encode($stat);
