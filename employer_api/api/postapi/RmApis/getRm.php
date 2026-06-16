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
require '../../vendor/phpmailer/PHPMailerAutoload.php';
include "../../../includes/db-connection/db.php";

$folderPath = "../uploads/employer-images/";
$json = file_get_contents('php://input');

$limitClause = '';
if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = 0;
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = 0;
    $skip = intval($_GET['skip']);
}
@$offset = $limit * $skip;
if (isset($_GET['limit']) && isset($_GET['skip'])) {
    $limitClause = "LIMIT $limit OFFSET $offset";
}


if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id = $_GET['id'];
    $singleRmData = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM tbl_resource_managers WHERE id="' . $id . '" AND status=1'));
    $singleRmData != null ?
        $stat = ['status' => true, 'data' => $singleRmData]
        :
        $stat = ['status' => false, 'message' => "No Data Found "];

    exit(json_encode($stat));
}


if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $empid = $_GET['empId'];

    $query = mysqli_query($con, "SELECT * FROM tbl_resource_managers WHERE (employer_auto_create_id='" . $empid . "' OR RM_auto_create_id='" . $empid . "') ORDER BY id DESC $limitClause");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_resource_managers WHERE (employer_auto_create_id='" . $empid . "' OR RM_auto_create_id='" . $empid . "') ORDER BY id DESC"));

    $data = [];

    while ($fetchRmData = mysqli_fetch_assoc($query)) {
        $data[] = $fetchRmData;
    }
    $data != null ?
        $stat = ['status' => true, 'data' => $data, 'count' => $count]
        :
        $stat = ['status' => true, 'message' => 'No Data Found'];
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);
