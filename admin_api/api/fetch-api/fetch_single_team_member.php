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


// $con=mysqli_connect('localhost','u237107965_jaldihire_db','Jaldihire@123','u237107965_jaldihire_db');

@$id = $_GET['teammember'];

$sqilQuery = "SELECT * FROM tbl_allyted_team WHERE status=1";

if (!empty($id)) {
    $sqilQuery = "SELECT * FROM tbl_allyted_team WHERE allyted_member_id ='" . $id . "' && status=1";
}


$get_team = mysqli_query($con, $sqilQuery);
$data = [];
while ($fetchteam = mysqli_fetch_array($get_team)) {

    if (!empty($id)) {
        $totalApplicants = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assigned_jobs_list WHERE assigned_agent='" . $id . "' "));

        $totalOnboarded = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE jobrecruiterid='" . $id . "'"));

        $fetchteam['totalApplicants'] = $totalApplicants;
        $fetchteam['onBoardedApplicants'] = $totalOnboarded;
    }

    $data[] = $fetchteam;
}
echo json_encode($data);
