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
$response = TokenVerify($token);
if ($response['role'] != 'Recruiter') {
    exit(json_encode(['status' => false, 'message' => 'Invalid role']));
}

if (isset($_GET['scheduled_id']) && !empty($_GET['scheduled_id'])) {
    $id = $_GET['scheduled_id'];

    $query = "SELECT *,T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE scheduled.schedule_id='" . $id . "' AND applicant.applicant_id=scheduled.applicant_id AND scheduled.status='0' AND T3.id = scheduled.round";

    $data = mysqli_fetch_assoc(mysqli_query($con, $query));

    if ($data != null) {
        $status = ['status' => true, 'data' => $data];
    } else {
        $status = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);
