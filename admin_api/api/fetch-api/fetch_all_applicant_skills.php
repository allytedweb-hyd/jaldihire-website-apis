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

// include "includes/db.php";
include "../../includes/db-connection/db.php";




$get_team = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE status=1 ");
$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE  status=1"));

$data = [];
while ($fetchteam = mysqli_fetch_array($get_team)) {
    $data[] = $fetchteam;
}

if ($data != null) {
    $status = ['status' => true, 'data' => $data, 'count' => $count];
} else {
    $status = ['status' => false, 'message' => 'No Data Found'];
}
echo json_encode($status);
