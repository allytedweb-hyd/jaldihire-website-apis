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

if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {

    $agentId = $_GET['agent_id'];
    $data = [];

    $getApplicants = mysqli_query($con, "SELECT T2.profile_image,T2.full_name,T3.id as job_id,T3.auto_create_jobid,T2.applicant_auto_create_id FROM assigned_jobs_list T1, tbl_applicant_add T2, tbl_post_a_job T3 WHERE T1.assigned_agent ='" . $agentId . "' AND T1.status=1 AND T1.admin_status=0 AND T2.applicant_id = T1.assigned_applicant_id AND BINARY T3.auto_create_jobid= BINARY T1.assigned_job_id");


    while ($fet = mysqli_fetch_array($getApplicants)) {
        $data[] = $fet;
    }

    if ($data != null) {
        $stat = ['status' => true, 'data' => $data];
    } else {
        $stat = ['status' => false, 'message' => 'No Applicants Found'];
    }
} else {
    $stat = ['status' => true, 'message' => 'Invalid Params'];
}

echo json_encode($stat);
