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

include "../includes/db-connection/db.php";
include "./lib/jwt.php";

date_default_timezone_set("Asia/Calcutta");

// Support both Apache and Nginx for auth header
$authHeader = '';
if (function_exists('apache_request_headers')) {
    $headers    = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
}
if (!$authHeader && isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
}
if (!$authHeader && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

$token = str_replace('Bearer ', '', $authHeader);
TokenVerify($token);

$json = file_get_contents('php://input');

if (empty($json)) {
    echo json_encode(['status' => false, 'message' => 'No data received']);
    exit();
}

$data = json_decode($json);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => false, 'message' => 'Invalid JSON: ' . json_last_error_msg()]);
    exit();
}

// Accept both single object {} and array of objects [{}]
$items = is_array($data) ? $data : [$data];

if (count($items) === 0) {
    echo json_encode(['status' => false, 'message' => 'Empty array received']);
    exit();
}

$results  = [];
$inserted = 0;
$skipped  = 0;
$failed   = 0;

foreach ($items as $index => $item) {

    if (!isset($item->technology) || trim($item->technology) === '') {
        $results[] = [
            'index'      => $index,
            'technology' => null,
            'status'     => false,
            'message'    => 'Missing or empty technology field'
        ];
        $failed++;
        continue;
    }

    $technologyName = mysqli_real_escape_string($con, trim($item->technology));

    // Check duplicate
    $check = mysqli_query($con,
        "SELECT id FROM tbl_technology WHERE technology = '$technologyName' AND status = 1"
    );

    if (mysqli_num_rows($check) > 0) {
        $results[] = [
            'index'      => $index,
            'technology' => $technologyName,
            'status'     => false,
            'message'    => 'Already exists'
        ];
        $skipped++;
        continue;
    }

    // Insert only into tbl_technology — no notification query
    $insert = mysqli_query($con,
        "INSERT INTO tbl_technology SET technology = '$technologyName', status = 1"
    );

    if ($insert) {
        $results[] = [
            'index'      => $index,
            'technology' => $technologyName,
            'status'     => true,
            'message'    => 'Added successfully'
        ];
        $inserted++;
    } else {
        $results[] = [
            'index'      => $index,
            'technology' => $technologyName,
            'status'     => false,
            'message'    => 'Insert failed: ' . mysqli_error($con)
        ];
        $failed++;
    }
}

echo json_encode([
    'status'  => true,
    'summary' => [
        'total'    => count($items),
        'inserted' => $inserted,
        'skipped'  => $skipped,
        'failed'   => $failed
    ],
    'data' => $results
]);