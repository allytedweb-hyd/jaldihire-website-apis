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
include '../../includes/db-connection/db.php';
include '../lib/jwt.php';

$headers = apache_request_headers();
date_default_timezone_set("Asia/Calcutta");


// Checking Token Present Or Not
if (!isset($headers['Authorization'])) {
    $status = ['status' => false, 'message' => 'Unauthorized Access'];
    echo json_encode($status);
    exit();
}
@$token = str_replace('Bearer ', '', $headers['Authorization']);

try {

    $get_emp_location = mysqli_query($con, "
    SELECT 
        MIN(location_id) as location_id,
        location_name,
        MIN(status) as status
    FROM tbl_employer_location_master
    WHERE status=1
    GROUP BY location_name
");

    $data = [];
    while ($fetch_emp_loaction = mysqli_fetch_array($get_emp_location)) {
        $data[] = $fetch_emp_loaction;
    }
    if ($data != null) {
        $status = ['status' => true, 'message' => 'Fetched Succesfully', 'data' => $data];
    } else {
        $status = ['status' => false, 'message' => 'No Data Found'];
    }
} catch (Exception $e) {
    $status = ['status' => false, 'message' => 'Some thing went wrong', "e" => mysqli_error($con)];
}

echo json_encode($status);
