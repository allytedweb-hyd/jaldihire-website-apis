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
include '../../includes/db-connection/db.php';

$limit = 3;
$skip = 0;

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = intval($_GET['skip']);
}
$offset = $limit * $skip;

if (!empty($_GET['employer_id']) && !empty($_GET['applicant_id'])) {

    $appli_id = $_GET['applicant_id'];
    $employer_id = $_GET['employer_id'];
    $data = [];

    $getRecords = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE applicant_id='" . $appli_id . "' &&  employer_id='" . $employer_id . "'LIMIT $limit OFFSET $offset");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE applicant_id='" . $appli_id . "' &&  employer_id='" . $employer_id . "'"));

    while ($fet = mysqli_fetch_assoc($getRecords)) {
        $data[] = $fet;
    }
    if ($data != null) {
        $stat = ['status' => true, 'data' => $data, 'count' => $count];
    } else {
        $stat = ['status' => false, 'message' => 'No Records Found'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);
