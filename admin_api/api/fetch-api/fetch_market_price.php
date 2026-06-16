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
// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);
$status = [];

if (isset($_GET['skill']) && !empty($_GET['skill'])) {
    $skillData = [];
    $getPrices = mysqli_query($con, "SELECT experience,price,id FROM tbl_market_price WHERE skill='" . $_GET['skill'] . "' && status=1");

    while ($fet = mysqli_fetch_assoc($getPrices)) {
        $skillData[] = $fet;
    }

    $skillData == null ? $status = ['status' => false, 'message' => 'No Data Found'] : $status = ['status' => true, 'data' => $skillData];
    exit(json_encode($status));
}

try {
    $get_market_price = mysqli_query($con, "SELECT * FROM tbl_market_price  WHERE status=1 ORDER BY id DESC");
    $data = [];
    while ($fetchMarketPrice = mysqli_fetch_array($get_market_price)) {
        $data[] = $fetchMarketPrice;
    }
    if ($data != null) {
        $status = ['status' => true, 'message' => 'Fetched Succesfully', 'data' => $data];
    } else {
        $status = ['status' => false, 'message' => 'No Data Found'];
    }
} catch (Exception $e) {
    $status = ['status' => false, 'message' => 'Something went wrong', "error" => mysqli_error($con)];
}
echo json_encode($status);
