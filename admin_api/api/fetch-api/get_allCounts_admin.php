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
// queries to get data 

$totalJobs = mysqli_query($con, "SELECT * FROM tbl_post_a_job");

$activeJobs = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1");

$totalApplicants = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE status=1");

$totalEMployers = mysqli_query($con, "SELECT * FROM tbl_add_employer WHERE status=1");


// counts 

$TotaljobsCount = mysqli_num_rows($totalJobs);

$ActiveJobs = mysqli_num_rows($activeJobs);

$applicantsCounts = mysqli_num_rows($totalApplicants);

$employersCount = mysqli_num_rows($totalEMployers);


$counts = ['totalJobs' => $TotaljobsCount, 'ActiveJobs' => $ActiveJobs, 'applicants' => $applicantsCounts, 'employers' => $employersCount];

$status = ['status' => true, 'data' => $counts];

echo json_encode($status);
