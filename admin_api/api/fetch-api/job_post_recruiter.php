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

if (isset($_GET['jobid']) && !empty($_GET['jobid'])) {

    $job_id = $_GET['jobid'];
    $recruiterIds = [];
    $data = [];

    $get_reruiter_data = mysqli_query($con, "SELECT * FROM tbl_allyted_team a,assign_job_recruiter b WHERE a.allyted_member_id = b.assigned_reqruiter AND b.assigned_job='" . $job_id . "' && a.status=1 LIMIT $limit OFFSET $offset");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_allyted_team a,assign_job_recruiter b WHERE a.allyted_member_id = b.assigned_reqruiter AND b.assigned_job='" . $job_id . "' && a.status=1"));

    while ($fetchrecruiters = mysqli_fetch_array($get_reruiter_data)) {
        $data[] = $fetchrecruiters;
        $recruiterIds[] = $fetchrecruiters['allyted_member_id'];
    }

    foreach ($recruiterIds as $key => $id) {
        $getSubstituteDetails = mysqli_query($con, "SELECT T1.*,T2.member_name as substitute_name FROM job_recruiter_substitute T1,tbl_allyted_team T2 WHERE T1.job_id='" . $job_id . "' && T1.present_recruiter='" . $id . "' && T2.allyted_member_id = T1.substitute_recruiter && (T1.status=2 OR T1.status=1) && T2.status=1");
        while ($fet = $getSubstituteDetails->fetch_assoc()) {
            $data[$key] += $fet;
        }
    }

    $status = ['status' => true, 'data' => $data, 'count' => $count];
} else {
    $status = ['status' => false, 'message' => "Missing Parameters"];
}
echo json_encode($status);
