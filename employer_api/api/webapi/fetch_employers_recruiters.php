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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../includes/db-connection/db.php";

// $employer_id = $_GET['employer_id'];

// if (!empty($employer_id)) {
//     $get_recruiters_data = mysqli_query($con, "select * from tbl_allyted_team a,assigned_Recruiters_list b where a.allyted_member_id = b.assigned_recruiter_id and b.assigned_employer_id='" . $employer_id . "'");
//     $data = [];
//     while ($fetchEmployers = mysqli_fetch_array($get_recruiters_data)) {
//         $data[] = $fetchEmployers;
//     }
//     $status = ['data' => $data];
// } else {
//     $status = ['error' => "Missing Parameters"];
// }

if (isset($_GET['applicant_id']) && !empty($_GET['applicant_id'])) {
    $applicantId = $_GET['applicant_id'];

    $get_recruiters_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT *,a.role as RecruiterRole, NULL as password FROM tbl_allyted_team a,applicant_recruiter_assigns b WHERE a.allyted_member_id = b.recruiter_assigned_id and b.applicant_assigned_id = '" . $applicantId . "' AND b.status=1 AND a.status=1"));
    $status = ['status' => true, 'Message' => 'fetched Successfully', 'data' => $get_recruiters_data];
} else {
    $status = ['status' => false, 'Message' => "Missing Parameters"];
}

echo json_encode($status);
