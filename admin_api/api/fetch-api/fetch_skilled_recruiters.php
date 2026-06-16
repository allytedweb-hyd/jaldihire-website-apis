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
    $limit = 0;
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = 0;
    $skip = intval($_GET['skip']);
}
@$offset = $limit * $skip;

if (isset($_GET['limit']) && isset($_GET['skip'])) {
    $limitClause = "LIMIT $limit OFFSET $offset";
}

$id = $_GET['id'];

$getSkills = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE id='" . $id . "'"));

$primarySkill = $getSkills['primary_skill'];

// $skills = explode(',', $getSkills['skill']);


$query = "SELECT * FROM tbl_allyted_team WHERE   skills LIKE '%" . $primarySkill . "%' && role='Recruiter' && status=1 $limitClause";


$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE  skills LIKE '%" . $primarySkill . "%' && role='Recruiter' && status=1"));

$getRecruiters = mysqli_query($con, $query);
$recritersList = [];

while ($data = mysqli_fetch_assoc($getRecruiters)) {
    $recritersList[] = $data;
}

if ($recritersList != null) {
    $status = ['status' => true, 'data' => $recritersList, 'count' => $count];
} else {
    $status = ['status' => false, 'message' => 'No Data Found', 'count' => $count];
}

echo json_encode($status);
