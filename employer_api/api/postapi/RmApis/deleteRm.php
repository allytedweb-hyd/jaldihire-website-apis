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

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $getDelId = $_GET['id'];

    $deleteId = mysqli_query($con, "UPDATE tbl_resource_managers  SET status=0 WHERE id='" . $getDelId . "'");

    if ($deleteId == true) {
        $stat = ['status' => true, 'message' => 'deteled Successfully',];
    } else {
        $stat = ['status' => false, 'message' => 'Failed to Delete',];
    }
}
echo json_encode($stat);
