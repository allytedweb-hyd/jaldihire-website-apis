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
// include "includes/db.php";
include "../../includes/db-connection/db.php";
$str = '';

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {

        $emp_id = $_GET['employer_id'];

        if (isset($_GET['roundName']) && !empty($_GET['roundName'])) {

                $query = 'SELECT * FROM tbl_add_team WHERE interview_round LIKE"%' . $_GET['roundName'] . '%" && employer_id="' . $emp_id . '" && status=1';
                $getaddteam = mysqli_query($con, $query);
        } else {
                $getaddteam = mysqli_query($con, "SELECT * FROM tbl_add_team WHERE employer_id='" . $emp_id . "' && status=1");
        }
        $data = [];
        while ($fetchaddteam = mysqli_fetch_array($getaddteam)) {
                $data[] = $fetchaddteam;
        }
} else {
        $data = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($data);
