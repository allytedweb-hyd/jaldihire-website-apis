<?php

use Cloudinary\Transformation\Offset;

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
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include "../../includes/db-connection/db.php";

// == '' ? 12 : (int)$_GET['limit'];

// @$limit = $_GET['limit'];

// @$offset = $limit * $skip;

// LIMIT $limit OFFSET $offset
$limitClause = '';


if (isset($_GET['limit']) && !empty($_GET['limit'])) {
        $limit = $_GET['limit'];
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
        $skip = $_GET['skip'];
}
@$offset = $limit * $skip;

if (isset($_GET['limit']) && isset($_GET['skip'])) {
        $limitClause = "LIMIT $limit OFFSET $offset";
}
$getlocation = mysqli_query($con, "SELECT * from tbl_roles WHERE status=1 ORDER BY role_name ASC $limitClause");
$count = mysqli_num_rows(mysqli_query($con, "SELECT * from tbl_roles WHERE status=1 ORDER BY role_name ASC"));

$data = [];
while ($fetchlocation = mysqli_fetch_array($getlocation)) {
        $data[] = $fetchlocation;
}

$data == null ? $stat = ['status' => false, 'message' => 'No Data Found'] : $stat = ['status' => true, 'data' => $data, 'count' => $count];
echo json_encode($stat);
