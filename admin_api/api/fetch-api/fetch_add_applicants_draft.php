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

@$uId = $_GET['id'];
$userExp = [];
$uskill = [];
$userCertificates = [];
$userUrls = [];
if (!empty($uId)) {
    $getData = mysqli_query($con, "select * , NULL as password from tbl_applicant_add_draft where applicant_auto_create_id='" . $uId . "' and status=0");
    $getExp = mysqli_query($con, "select * from applicant_experiences_draft where applicant_auto_create_id='" . $uId . "' and status=0");
    $getSkill = mysqli_query($con, "select * from applicant_skill_draft  where applicant_auto_create_id='" . $uId . "' and status=0");
    $getCertificates = mysqli_query($con, "SELECT * FROM applicant_certificates_drafts WHERE applicant_auto_create_id='" . $uId . "' and status=0");
    $getUrls = mysqli_query($con, "SELECT * FROM applicant_urls_draft WHERE applicant_auto_create_id='" . $uId . "' and status=0");

    while ($UrlData = mysqli_fetch_assoc($getUrls)) {
        $userUrls[] = $UrlData;
    }
    while ($Cdata = mysqli_fetch_assoc($getCertificates)) {
        $userCertificates[] = $Cdata;
    }
    while ($uData = mysqli_fetch_assoc($getData)) {
        $userData = $uData;
    }
    while ($uexp = mysqli_fetch_assoc($getExp)) {
        $userExp[] = $uexp;
    }
    while ($uskill = mysqli_fetch_assoc($getSkill)) {
        $userSkill[] = $uskill;
    }

    $userData['experiences'] = $userExp;
    $userData['skill'] = $userSkill;
    $userData['certificates'] = $userCertificates;
    $userData['Urls'] = $userUrls;
    $data = ['status' => true, 'data' => $userData];
    // echo json_encode($data);
    // exit();
} else {
    $data = ['status' => false, 'error' => 'No Data Found'];
}
echo json_encode($data);
