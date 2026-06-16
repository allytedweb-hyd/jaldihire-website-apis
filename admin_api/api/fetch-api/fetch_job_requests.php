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

$whereClause = '';
$status = [];
$data = [];
if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
    $agentId = $_GET['agent_id'];
    $whereClause = "T1.assigned_agent='" . $agentId . "' && T1.assigned_recruiter = '' &&";
} else if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    $recruiter_id = $_GET['recruiter_id'];
    $whereClause = "T1.assigned_recruiter='" . $recruiter_id . "' &&";
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
    echo json_encode($status);
    exit();
}

$getJobRequests = mysqli_query($con, "SELECT * FROM assigned_jobs_list T1, tbl_applicant_add T2 WHERE $whereClause T1.admin_status=0 && T1.status=0 && T2.applicant_id = T1.assigned_applicant_id && T2.status=1");
while ($fetch = mysqli_fetch_assoc($getJobRequests)) {
    $data[] = $fetch;
}

if ($data == null) {
    $status = ['status' => false, 'message' => 'No data found'];
} else {
    $status = ['status' => true, 'message' => 'fetched successfully', 'data' => $data];
}

echo json_encode($status);
