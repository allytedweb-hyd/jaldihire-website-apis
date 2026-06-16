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
include '../libs/jwt.php';
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
        echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
        exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


 $response=TokenVerify($token);

@$empId = ($_GET['employer_id'] == null) ? $response['userId'] : $_GET['employer_id'];


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

if (isset($empId) && !empty($empId)) {
        $data = [];

        $get_designations = mysqli_query($con, "SELECT * FROM tbl_employer_designation WHERE employer_id='" . $empId . "' AND status=1 ORDER BY designation ASC $limitClause");

        $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_employer_designation WHERE employer_id='" . $empId . "' AND status=1 ORDER BY designation ASC"));

        while ($fet = mysqli_fetch_array($get_designations)) {
                $data[] = $fet;
        }

        if ($data != null) {
                $stat = ['status' => true, 'data' => $data, 'count' => $count];
        } else {
                $stat = ['status' => false, 'message' => 'No Data Found'];
        }
} else {
        $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);
