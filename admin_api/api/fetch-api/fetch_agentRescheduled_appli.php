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

// $status = $_GET['status'];
@$limit = $_GET['limit'] ? $_GET['limit'] : 8;
@$skip = $_GET['skip'] ? $_GET['skip'] * $limit : 0;

if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
    @$agentId = $_GET['agent_id'];
    $whereClause = "scheduled.recruiter_auto_id='" . $agentId . "'";
} elseif (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    @$recruiter_id = $_GET['recruiter_id'];
    $whereClause = "scheduled.jobrecruiterid='" . $recruiter_id . "'";
} else {
    $stat = ['status' => false, 'Message' => 'Invalid Params'];
    echo json_encode($stat);
    exit;
}
$countQuery = "SELECT * FROM applicant_scheduled scheduled,tbl_applicant_add applicant WHERE applicant.applicant_id=scheduled.applicant_id && $whereClause && scheduled.resheduled_status=1";
$get_scheduled_count = mysqli_query($con, $countQuery);
$totalCount = mysqli_num_rows($get_scheduled_count);

$get_scheduled_interviews = mysqli_query($con, "SELECT * FROM applicant_scheduled scheduled,tbl_applicant_add applicant WHERE applicant.applicant_id=scheduled.applicant_id && $whereClause && scheduled.resheduled_status=1 limit $limit OFFSET $skip");
$data = [];
while ($fetchschedules = mysqli_fetch_array($get_scheduled_interviews)) {
    $data[] = $fetchschedules;
}
$status = ['data' => $data, 'count' => $totalCount];

echo json_encode($status);
