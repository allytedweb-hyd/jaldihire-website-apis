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

include "../../includes/db-connection/db.php";
include '../libs/jwt.php';

$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $frist_name = $data->frist_name;
    $last_name = $data->last_name;
    $number = $data->number;
    $email = $data->email;
    $company = $data->company;
    $package = $data->package;

    $insert_data = mysqli_query($con, "INSERT INTO tbl_create_account SET f_name='" . $frist_name . "',l_name='" . $last_name . "', number='" . $number . "',email='" . $email . "',company='" . $company . "',package='" . $package . "',status=1");

    if ($insert_data == true) {
        $status = ['status' => true, 'message' => 'Added Successfully !'];
    } else {
        $status = ['status' => false, 'message' => 'Failed! Try Again'];
    }
} else {
    $status = ['status' => false, 'message' => 'Something went wrong! please try again'];
}
echo json_encode($status);
