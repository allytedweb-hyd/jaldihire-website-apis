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
$limit = 5;
$skip = 0;

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
        $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
        $skip = intval($_GET['skip']);
}
$offset = $limit * $skip;
$clause = '';
$roleClause = '';
$status = [];
// condition to get only agents for agent substitute
if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
        $clause = "allyted_member_id != '" . $_GET['agent_id'] . "' AND role='Agent' AND";
}
// condition to get only recruiters for recruiter substitute
if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
        $clause = "allyted_member_id != '" . $_GET['recruiter_id'] . "' AND role='Recruiter' AND";
}
if (isset($clause) && !empty($clause)) {
        $data = [];
        $query = "SELECT * FROM tbl_allyted_team WHERE $clause status=1";
        $agentsData = mysqli_query($con, $query);
        while ($fetchteam = mysqli_fetch_array($agentsData)) {
                $data[] = $fetchteam;
        }
        $status = ['status' => true, 'data' => $data];
        echo json_encode($status);
        exit();
}

// condition to get Data my role 
if (isset($_GET['role']) && !empty($_GET['role'])) {
        $roleClause = "role='" . $_GET['role'] . "' && ";
}

$get_team = mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE $roleClause status=1 ORDER BY allyted_member_id DESC LIMIT $limit OFFSET $offset  ");

$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE $roleClause status=1"));

$data = [];
while ($fetchteam = mysqli_fetch_array($get_team)) {
        $data[] = $fetchteam;
}

if ($data != null) {
        $status = ['status' => true, 'data' => $data, 'count' => $count];
} else {
        $status = ['status' => false, 'message' => 'No Data Found'];
}

echo json_encode($status);
