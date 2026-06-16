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
$jobData = [];
$getJobsWithOutAgents = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid NOT IN (SELECT auto_create_jobid FROM employer_agent_relation WHERE status=1) ORDER BY id DESC");

while ($fet = $getJobsWithOutAgents->fetch_assoc()) {
    $jobData[] = $fet;
}
if ($jobData != null) {
    $status = ['status' => true, 'data' => $jobData];
} else {
    $status = ['status' => false, 'message' => 'No data Found'];
}

echo json_encode($status);
