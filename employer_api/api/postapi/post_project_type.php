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

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $project_type = $data->projectType;
    // @$employer_id = $data->employer_id;

    // if (!empty($employer_id)) {
    $insert = mysqli_query($con, "INSERT INTO tbl_employer_project_type SET project_type='" . $project_type . "', status=1 ");

    if ($insert == true) {
        $stat = ['status' => true, 'message' => 'Posted Successfully'];
    } else {
        $stat = ['status' => false, 'message' => 'Failed! Please Try Again'];
    }
    // } else {
    //     $stat = ['status' => false, 'message' => 'Invalid Employer Id'];
    // }

} else {
    $stat = ['status' => false, 'message' => 'Something Went Wrong! Please Try Again'];
}
echo json_encode($stat);
