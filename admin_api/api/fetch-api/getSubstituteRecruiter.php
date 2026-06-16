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

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $jobId = $_GET['job_id'];
    $data = [];
    $getRecruiters = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM job_recruiter_substitute T1, tbl_allyted_team T2, tbl_post_a_job T3 WHERE T1.job_id='" . $jobId . "'&& T2.allyted_member_id = T1.present_recruiter  && T3.id = T1.job_id && (T1.status=2 OR T1.status=1)"));

    // $getAgents += mysqli_fetch_assoc(mysqli_query($con, "SELECT member_name as SubstituteAgent FROM tbl_allyted_team WHERE allyted_member_id='" . $getAgents['substitute_agent'] . "'"));

    if ($getRecruiters != null) {
        $status = ['status' => true, 'data' => $getRecruiters];
    } else {
        $status = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);
