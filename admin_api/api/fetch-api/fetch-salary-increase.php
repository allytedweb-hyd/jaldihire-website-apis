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

$appId = $_GET["appId"];



$get_applicants_exp = mysqli_query($con, "select starting_date,ending_date,starting_salary,ending_salary,exp_company from applicant_experiences where applicant_id='" . $appId . "' && status=1");
$data = [];
while ($fetchapplicantsexp = mysqli_fetch_array($get_applicants_exp)) {
        $data[] = $fetchapplicantsexp;
}
echo json_encode($data);
