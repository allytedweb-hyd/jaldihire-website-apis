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

@$uId = $_GET['applicant'];

if (!empty($uId)) {
    $userExp = [];
    $userSkill = [];
    $getData = mysqli_query($con, "select * from tbl_applicant_add where applicant_id='" . $uId . "' and status=0");
    $getExp = mysqli_query($con, "select * from applicant_experiences where applicant_id='" . $uId . "' and status=1");
    $getSkill = mysqli_query($con, "select DISTINCT pri_skill,skill_rating from applicant_skill  where applicant_id='" . $uId . "' and status=1");
    while ($uData = mysqli_fetch_array($getData)) {
        $userData = $uData;
    }
    while ($uexp = mysqli_fetch_array($getExp)) {
        $userExp[] = $uexp;
    }
    while ($uskill = mysqli_fetch_array($getSkill)) {
        $userSkill[] = $uskill;
    }
    $data = ['status' => true, 'data' => $userData, "experience" => $userExp, "skill" => $userSkill];
    echo json_encode($data);
    exit();
}

$get_applicants = mysqli_query($con, "select * from tbl_applicant_add where status=0 and added_by_admin=0");
$data = [];
while ($fetchapplicants = mysqli_fetch_array($get_applicants)) {
    $data[] = $fetchapplicants;
}
echo json_encode(array_values($data));
