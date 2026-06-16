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


TokenVerify($token);
date_default_timezone_set("Asia/Calcutta");
$json = file_get_contents('php://input');
// Checking Token Present Or Not
// if (!isset($headers['Authorization'])) {
//     $status = ['status' => false, 'message' => 'Unauthorized Access'];
//     echo json_encode($status);
//     exit();
// }

// @$token = str_replace('Bearer ', '', $headers['Authorization']);

// if ($token) {
//     // Validating JWT Token
//     $reponse = verifyJWT($token);
//     if ($reponse['status']) {

//         $json = file_get_contents('php://input');
//         $data = json_decode($json);

if (isset($json) && !empty($json)) {

    $data = json_decode($json);

    $companyName = $data->company_name;
    $mobile = $data->mobile;
    $gst_no = $data->gst;
    $email = $data->email;
    $createdBy = $reponse['userId'];
    $createDate = date("Y-m-d H:i:s");

    $fetchBlackList = mysqli_query($con, "SELECT * FROM tbl_blacklist_master WHERE company_name='" . $companyName . "' && status=1");

    if (mysqli_num_rows($fetchBlackList) < 1) {
        $insert_company = mysqli_query($con, "INSERT INTO tbl_blacklist_master SET company_name='" . $companyName . "',mobile='" . $mobile . "',email='" . $email . "',gts_no='" . $gst_no . "',created_by='" . $createdBy . "',created_date='" . $createDate . "',status=1");
        if ($insert_company == true) {
            $text = "BlackListed Company <' . $companyName . '> added successfully";
            $blacklist_master = mysqli_query($con, 'INSERT INTO tbl_notification SET notify_to="All",notification_type="info",notification_text="' . $text . '",activity="Masters"');

            $stat = ['message' => 'Added Successfully ', 'status' => true];
        } else {
            $stat = ['status' => false, 'message' => 'Failed!'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'Company Already Exists!'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong please try again'];
}



echo json_encode($stat);
