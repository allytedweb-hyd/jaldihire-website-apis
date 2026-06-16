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
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);

TokenVerify($token);


// @$id = $_GET['applicant'];
$sqilQuery = '';

$data = [];

// $sqilQuery = "select * from tbl_add_employer_draft where status=0 ORDER BY employer_id DESC";
// if (!empty($id)) {
$sqilQuery = "select * from tbl_add_employer_draft where status=0 ORDER BY employer_id DESC";
// 
// }


$get_employers = mysqli_query($con, $sqilQuery);
while ($fetchemployers = mysqli_fetch_assoc($get_employers)) {
    $query = mysqli_query($con, "SELECT * FROM tbl_employer_relation_draft WHERE employer_id='" . $fetchemployers['employer_id'] . "' && status=0");
    $relations = [];
    while ($relation = mysqli_fetch_assoc($query)) {
        $relations[] = $relation;
    }
    $fetchemployers['relations'] = $relations;
    $data[] = $fetchemployers;
}
echo json_encode($data);
