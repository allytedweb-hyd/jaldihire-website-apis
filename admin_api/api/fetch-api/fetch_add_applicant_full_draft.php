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


@$experienceFrom = $_GET['experienceFrom'];
@$experienceTo = $_GET['experienceTo'];
@$skill = $_GET['skill'];
@$avaliability = $_GET['avaliability'];
@$location = $_GET['location'];
@$limit = $_GET['limit'] ? $_GET['limit'] : 10;
@$skip = $_GET['skip'] ? $_GET['skip'] : 0;
$offset = $skip * $limit;

$whereClause = '';
$idClause = '';

if (isset($_GET['admin_id']) && !empty($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];
    $idClause = "created_by_id='" . $admin_id . "' &&( created_by_role='Admin' OR  created_by_role='Super Admin')&&";
} elseif (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    $recruiter_id = $_GET['recruiter_id'];
    $idClause = "created_by_id='" . $recruiter_id . "' && created_by_role='Recruiter' &&";
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
    exit(json_encode($status));
}

if (!empty($experienceFrom)) {
    $whereClause .= "&&total_expereience >= '" . $experienceFrom . "'";
}
if (!empty($experienceFrom)) {
    $whereClause .= "&&total_expereience <= '" . $experienceTo . "'";
}
if (!empty($skill)) {
    $whereClause .= "&&primary_skills LIKE '%" . $skill . "%'";
}
if (!empty($location)) {
    $whereClause .= "&&preferred_location = '" . $location . "'";
}
if (!empty($avaliability)) {
    $whereClause .= "&&notice_period <= '" . $avaliability . "'";
}

@$uId = $_GET['id'];

if (!empty($uId)) {
    $getData = mysqli_query($con, "select * from tbl_applicant_add_draft where applicant_auto_create_id='" . $uId . "' and status=0 ORDER BY applicant_id DESC ");
    $getExp = mysqli_query($con, "select * from applicant_experiences_draft where applicant_auto_create_id='" . $uId . "' and status=0");
    $getSkill = mysqli_query($con, "select DISTINCT pri_skill from applicant_skill_draft  where applicant_auto_create_id='" . $uId . "' and status=0");
    $getCertificates = mysqli_query($con, "SELECT certificate_image FROM applicant_certificates_drafts WHERE applicant_auto_create_id='" . $uId . "' and status=0");

    while ($Cdata = mysqli_fetch_assoc($getCertificates)) {
        $userCertificates = $Cdata;
    }
    while ($uData = mysqli_fetch_array($getData)) {
        $userData = $uData;
    }
    while ($uexp = mysqli_fetch_array($getExp)) {
        $userExp[] = $uexp;
    }
    while ($uskill = mysqli_fetch_array($getSkill)) {
        $userSkill[] = $uskill;
    }
    $data = ['status' => false, 'data' => $userData, "experience" => $userExp, "skill" => $userSkill];
    echo json_encode(['status' => false, 'data' => $userData, "experience" => $userExp, "skill" => $userSkill, 'certificates' => $userCertificates]);
    exit();
}

$query = "select * from tbl_applicant_add_draft where $idClause status = 0 ORDER BY applicant_id DESC $whereClause  LIMIT $limit OFFSET $offset";
// echo $query;
// exit();



$get_applicants = mysqli_query($con, $query);
$data = [];
while ($fetchapplicants = mysqli_fetch_array($get_applicants)) {
    $data[] = $fetchapplicants;
}
echo json_encode(array_values($data));
