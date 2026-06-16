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
include '../libs/jwt.php';
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


TokenVerify($token);
$techId = '';

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {

    if (isset($_GET['technologyId']) && !empty($_GET['technologyId'])) {
        $techId = "AND technology='" . $_GET['technologyId'] . "'";
    }

    $data = [];

    $q = "SELECT primary_skill as skill_name FROM tbl_post_a_job WHERE company_Id='" . $_GET['employer_id'] . "' $techId GROUP BY primary_skill";


    $getSkills = mysqli_query($con, $q);

    while ($f = mysqli_fetch_assoc($getSkills)) {
        $data[] = $f;
    }

    if ($data != null) {
        $status = ['status' => true, 'data' => $data];
    } else {
        $status = ['status' => false, 'message' => 'No skills Found'];
    }
} else {
    $status = ['status' => true, 'message' => 'Invalid Params'];
}
echo json_encode($status);
