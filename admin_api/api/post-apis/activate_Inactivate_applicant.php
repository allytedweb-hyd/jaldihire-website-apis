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
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);



$response = TokenVerify($token);
if ($response['role'] != 'Recruiter') {
    exit(json_encode(['status' => false, 'message' => 'Invalid role']));
}

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $applicant_id = $data->applicant;
    $status = $data->status;

    $query = "UPDATE tbl_applicant_add SET on_boarded='" . $status . "' WHERE applicant_id='" . $applicant_id . "' AND status=1";
    $updateApplicant = mysqli_query($con, $query);

    $updateApplicant == true ?
        $stat = ['status' => true, 'message' => $status == 1 ? 'Applicant Inactivated' : 'Applicant Activated']
        :
        $stat = ['status' => false, 'message' => 'Failed Try Again'];
} else {
    $stat = ['status' => false, 'message' => 'Something Went Wrong! Try Again'];
}
echo json_encode($stat);
