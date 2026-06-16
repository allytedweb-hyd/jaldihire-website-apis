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

if (isset($_GET['exp']) && !empty($_GET['exp'])) {
    $exp = $_GET['exp'];

    $meanprice = intval($exp) * 3;
    $lessprice = $meanprice - 1;
    $moreprice = $meanprice + 1;
    $data = [];

    $q = "SELECT * FROM tbl_post_a_job WHERE price BETWEEN   $lessprice AND $moreprice AND status=1 ";

    $fetJobDetails = mysqli_query($con, $q);

    while ($fet = $fetJobDetails->fetch_assoc()) {
        $data[] = $fet;
    }
    if ($data == null) {
        $status = ['status' => false, 'message' => 'No Jobs Found'];
    } else {
        $status = ['status' => true, 'data' => $data];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);
