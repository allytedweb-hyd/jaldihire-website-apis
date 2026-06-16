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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../includes/db-connection/db.php";

@$id = $_GET['id'];
@$jobId = $_GET['jobId'];
@$employerId = $_GET['employerId'];
@$agentId = $_GET['agent_id'];
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
$agentClause = '';
$clause = '';
$ids = [];


if (isset($agentId) && !empty($agentId)) {
    $agentClause = "assigned_recruiter_id != '" . $agentId . "'&&";
}
if (isset($employerId) && !empty($employerId)) {

    if (isset($_GET['skill']) && !empty($_GET['skill'])) {
        $skl = $_GET['skill'];
        $clause = "skills LIKE '%$skl%' &&";
    }

    $AgentQuery = "SELECT assigned_recruiter_id FROM assigned_Recruiters_list WHERE assigned_employer_id='" . $employerId . "' && $agentClause status=1";

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

// if (count($ids) > 0) {
$idsStr = join("','", $ids);
$whereClause2 = "allyted_member_id IN ('" . $idsStr . "') &&";
// }


// $whereClause2

$query = "SELECT * FROM tbl_allyted_team WHERE $whereClause2 $clause role='Agent' && status=1";
// echo json_encode($query);
// exit;

$getAgents = mysqli_query($con, $query);
$agentsList = [];

while ($data = mysqli_fetch_assoc($getAgents)) {
    $agentsList[] = $data;
}

$status = ['status' => true, 'message' => 'Fetched successfully', 'data' => $agentsList];

echo json_encode($status);
