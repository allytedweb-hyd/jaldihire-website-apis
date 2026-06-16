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
include '../../includes/db-connection/db.php';
include '../lib/jwt.php';

$headers = apache_request_headers();
date_default_timezone_set("Asia/Calcutta");

// Checking Token Present Or Not
if (!isset($headers['Authorization'])) {
    $status = ['status' => false, 'message' => 'Unauthorized Access'];
    echo json_encode($status);
    exit();
}

@$token = str_replace('Bearer ', '', $headers['Authorization']);

if ($token) {
    // Validating JWT Token
    $reponse = verifyJWT($token);
    if ($reponse['status']) {

        @$limit = $_GET['limit'];
        if (isset($limit) && !empty($limit)) {
            @$limit = $_GET['limit'] == '' ? 6 : (int) $_GET['limit'];
            @$offset = $limit * $skip;
            try {
                $get_companies = mysqli_query($con, "SELECT * FROM tbl_blacklist_master WHERE status=1 LIMIT $limit OFFSET $offset");
                $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_blacklist_master WHERE status=1"));
                $data = [];
                while ($fet = mysqli_fetch_array($get_companies)) {
                    $data[] = $fet;
                }
                if ($data != null) {
                    $status = ['status' => true, 'data' => $data, 'count' => $count];
                } else {
                    $status = ['status' => false, 'message' => 'No Data Found'];
                }
            } catch (Exception $e) {
                $status = ['status' => false, 'message' => 'Some thing went wrong', "error" => mysqli_error($con)];
            }
        } else {
            $get_companies = mysqli_query($con, "SELECT * FROM tbl_blacklist_master WHERE status=1");
            $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_blacklist_master WHERE status=1"));
            $data = [];
            while ($fet = mysqli_fetch_array($get_companies)) {
                $data[] = $fet;
            }
            if ($data != null) {
                $status = ['status' => true, 'data' => $data, 'count' => $count];
            } else {
                $status = ['status' => false, 'message' => 'No Data Found'];
            }
        }
    } else {
        $status = ['status' => false, 'message' => 'User Deactivated or Invalid'];
    }
} else {
    $status = ['status' => false, 'message' => 'Unauthorized Access'];
}
echo json_encode($status);
