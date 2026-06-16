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

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
    $employer_id = $_GET['employer_id'];
    $employershirings = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE employer_id='" . $employer_id . "' && status=6 ");

    $data = [];
    while ($fetchapplicants = mysqli_fetch_array($employershirings)) {
        $data[] = $fetchapplicants;
    }

    if ($data == null) {
        $data = ['status' => false, 'Message' => 'No data found'];
    } else {
        $data = ['status' => true, 'Message' => 'fetched Successfully', 'data' => $data];
    }
} else {
    $data = ['status' => false, 'Message' => 'Invalid Params'];
}

echo json_encode($data);
