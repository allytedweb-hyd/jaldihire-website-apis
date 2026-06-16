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

include "../../includes/db-connection/db.php";
include "../lib/jwt.php";

// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);

TokenVerify($token);
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

$get_designation = mysqli_query($con, "SELECT * FROM tbl_designation WHERE status=1  ORDER BY designation ASC $limitClause");

$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_designation WHERE status=1 ORDER BY designation ASC"));

$data = [];
while ($fetchdesignation = mysqli_fetch_array($get_designation)) {
        $data[] = $fetchdesignation;
}

$data == null ? $stat = ['status' => false, 'message' => 'No Data Found'] : $stat = ['status' => true, 'data' => $data, 'count' => $count];


echo json_encode($stat);
