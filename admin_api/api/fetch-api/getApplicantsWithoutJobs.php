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
$skils = '';

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


if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
    $id = $_GET['agent_id'];
} else if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    $id = $_GET['recruiter_id'];
} else {
    $status = ['status' => false, 'message' => "Invalid Params"];
    echo json_encode($status);
    exit();
}

$getAgentSkills = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE allyted_member_id='" . $id . "' && status=1"));
$skillsArray = explode(',', $getAgentSkills['skills']);

foreach ($skillsArray as $key => $s) {
    if (intval(count($skillsArray) - 1) == $key) {
        $skils .= "'" . $s . "'";
    } else {
        $skils .= "'" . $s . "'" . ",";
    }
}
$data = [];

$getApplicantIds = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE main_skill IN ( $skils ) AND applicant_id NOT IN (SELECT assigned_applicant_id FROM assigned_jobs_list) $limitClause");

$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE main_skill IN ( $skils ) AND applicant_id NOT IN (SELECT assigned_applicant_id FROM assigned_jobs_list)"));

while ($row = $getApplicantIds->fetch_assoc()) {
    $data[] = $row;
}

$status = ['status' => true, 'data' => $data, 'count' => $count];
echo json_encode($status);
