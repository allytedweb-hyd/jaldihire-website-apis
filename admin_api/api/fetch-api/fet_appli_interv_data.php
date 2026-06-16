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

if (isset($_GET['applicant_id']) && !empty($_GET['applicant_id'])) {
    $appli_id = $_GET['applicant_id'];
    $data = [];

    $getRecords = mysqli_query($con, "SELECT *,T2.company_name as employerName,T1.status as InterviewStatus FROM applicant_scheduled T1,tbl_add_employer T2 WHERE applicant_id='" . $appli_id . "' && T2.employer_auto_create_id = T1.employer_id && T2.status=1");

    while ($fet = mysqli_fetch_assoc($getRecords)) {
        $data[] = $fet;
    }
    if ($data != null) {
        $stat = ['status' => true, 'data' => $data];
    } else {
        $stat = ['status' => false, 'message' => 'No Records Found'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);
