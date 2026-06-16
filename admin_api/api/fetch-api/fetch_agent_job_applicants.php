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
@$limit = 9;
@$skip = 0;

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = intval($_GET['skip']);
}
$offset = $limit * $skip;


if (isset($_GET['jobId']) && !empty($_GET['jobId'])) {

    $jobId = $_GET['jobId'];
    $data = [];

    $get_jobapplicants = mysqli_query($con, "SELECT * FROM tbl_applicant_add a,assigned_jobs_list b WHERE a.applicant_id = b.assigned_applicant_id AND b.assigned_job_id='" . $jobId . "' && b.status=1 LIMIT $limit OFFSET $offset");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add a,assigned_jobs_list b WHERE a.applicant_id = b.assigned_applicant_id AND b.assigned_job_id='" . $jobId . "' && b.status=1"));

    while ($fetchapplicants = mysqli_fetch_array($get_jobapplicants)) {
        $data[] = $fetchapplicants;
    }

    $status = ['status' => true, 'data' => $data, 'count' => $count];
} else {
    $status = ['status' => false, 'error' => "Missing Parameters"];
}
echo json_encode($status);
