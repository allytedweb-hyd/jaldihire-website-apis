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

$employerIds = [];

if (isset($_GET['agentId']) && !empty($_GET['agentId'])) {

    $query = mysqli_query($con, "SELECT * FROM assigned_Recruiters_list WHERE assigned_recruiter_id='" . $_GET['agentId'] . "' && status=1 ORDER BY assigned_id DESC $limitClause");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assigned_Recruiters_list WHERE assigned_recruiter_id='" . $_GET['agentId'] . "' && status=1 ORDER BY assigned_id DESC"));

    if (mysqli_num_rows($query) > 0) {

        while ($row = mysqli_fetch_assoc($query)) {
            $employerIds[] = $row['assigned_employer_id'];
        }

        $data = [];

        for ($i = 0; $i < count($employerIds); $i++) {

            $get_employers = mysqli_query($con, "SELECT * FROM tbl_add_employer WHERE employer_id='" . $employerIds[$i] . "' && status=1");

            while ($fetchEmployers = mysqli_fetch_assoc($get_employers)) {

                $getAssignedJobsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM employer_agent_relation WHERE employer_id='" . $employerIds[$i] . "' AND  agent_id='" . $_GET['agentId'] . "' && status=1"));

                $fetchEmployers['jobsCount'] = $getAssignedJobsCount;

                $data[] = $fetchEmployers;
            }
        }
        if ($data == null) {
            $status = ['status' => false, 'message' => 'No data found'];
        } else {
            $status = ['status' => true, 'data' => $data, 'count' => $count];
        }
    } else {
        $status = ['status' => false, 'message No employers Assigned'];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);
