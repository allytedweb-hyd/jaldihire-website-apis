<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

/* ===== GET AUTH TOKEN ===== */

$headers = function_exists('apache_request_headers')
    ? apache_request_headers()
    : [];

$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

$token = str_replace('Bearer ', '', $authHeader);

/* ===== VERIFY TOKEN ===== */

TokenVerify($token);

/* ===== VALIDATE INPUT ===== */

if (!isset($_GET['applicant']) || empty($_GET['applicant'])) {

    echo json_encode([
        "status" => false,
        "message" => "Applicant ID is required"
    ]);
    exit;
}

$applicant_id = mysqli_real_escape_string($con, $_GET['applicant']);

/* ===== GET APPLICANT DATA ===== */

$query = "
SELECT a.*
FROM tbl_applicant_add a
LEFT JOIN applicant_skill c 
ON a.applicant_auto_create_id = c.applicant_auto_create_id
WHERE a.applicant_id = '$applicant_id'
LIMIT 1
";

$get_applicants = mysqli_query($con, $query);

/* ===== QUERY ERROR ===== */

if (!$get_applicants) {

    echo json_encode([
        "status" => false,
        "message" => "Database query failed",
        "error" => mysqli_error($con)
    ]);
    exit;
}

/* ===== NO DATA FOUND ===== */

if (mysqli_num_rows($get_applicants) == 0) {

    echo json_encode([
        "status" => false,
        "message" => "Applicant not found"
    ]);
    exit;
}

$data = mysqli_fetch_assoc($get_applicants);

/* ===== GET SKILLS ===== */

$userSkill = [];

$getSkill = mysqli_query(
    $con,
    "SELECT * 
     FROM applicant_skill  
     WHERE applicant_auto_create_id = '".$data['applicant_auto_create_id']."'
     AND status = 1"
);

/* ===== SKILL QUERY ERROR ===== */

if (!$getSkill) {

    echo json_encode([
        "status" => false,
        "message" => "Skill query failed",
        "error" => mysqli_error($con)
    ]);
    exit;
}

while ($uskill = mysqli_fetch_assoc($getSkill)) {
    $userSkill[] = $uskill;
}

/* ===== ADD SKILLS ===== */

$data['skill'] = $userSkill;

/* ===== FINAL RESPONSE ===== */

echo json_encode([
    "status" => true,
    "message" => "Applicant data fetched successfully",
    "data" => $data
]);