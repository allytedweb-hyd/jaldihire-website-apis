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


@$id = $_GET['id'];
@$jobId = $_GET['jobId'];
@$employerId = $_GET['employerId'];
$whereClause = '';

if (isset($id) && !empty($id)) {

    $getSkills = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE id='" . $id . "'");
    $fetch = mysqli_fetch_assoc($getSkills);

    $skills = explode(',', $fetch['skill']);
    $str = '';
    foreach ($skills as $key => $skill) {
        if ($skill != '') {
            // echo $key;
            if (count($skills) - ($key) == 2) {
                $str .= "'" . $skill . "'";
            } else {
                $str .= "'" . $skill . "',";
            }
        }
    }
    $whereClause = "skills IN($str) &&";
}

$whereClause2 = '';
$ids = [];

if (isset($employerId) && !empty($employerId)) {
    $AgentQuery = "SELECT assigned_recruiter_id FROM assigned_Recruiters_list WHERE assigned_employer_id='" . $employerId . "' && status=1";

    $getAgents = mysqli_query($con, $AgentQuery);

    while ($data = mysqli_fetch_assoc($getAgents)) {
        $ids[] = $data['assigned_recruiter_id'];
    }
}



if (isset($jobId) && !empty($jobId)) {
    $JOBquery = "SELECT agent_id FROM employer_agent_relation WHERE auto_create_jobid='" . $jobId . "' && status=1 GROUP BY agent_id";

    $getAgents = mysqli_query($con, $JOBquery);

    while ($data = mysqli_fetch_assoc($getAgents)) {
        $ids[] = $data['agent_id'];
    }
}

if (count($ids) > 0) {
    $idsStr = join("','", $ids);
    $whereClause2 = "allyted_member_id  NOT IN ('" . $idsStr . "') &&";
}


// $whereClause2

$query = "SELECT * FROM tbl_allyted_team WHERE  role='Agent' && status=1 $limitClause";

$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE  role='Agent' && status=1"));

// echo $query;
// exit;

$getAgents = mysqli_query($con, $query);
$agentsList = [];

while ($data = mysqli_fetch_assoc($getAgents)) {
    $agentsList[] = $data;
}

$status = ['status' => true, 'data' => $agentsList, 'count' => $count];

echo json_encode($status);
