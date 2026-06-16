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

date_default_timezone_set("Asia/Calcutta");

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

$token = str_replace('Bearer ', '', $authHeader);
TokenVerify($token);

$json = file_get_contents('php://input');

if (!isset($json) || empty($json)) {
    echo json_encode(['status' => false, 'message' => 'No data received']);
    exit();
}

$data = json_decode($json);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => false, 'message' => 'Invalid JSON format']);
    exit();
}

// Accept both single object and array of objects
$items = is_array($data) ? $data : [$data];

if (count($items) === 0) {
    echo json_encode(['status' => false, 'message' => 'Empty array received']);
    exit();
}

$results     = [];
$inserted    = 0;
$skipped     = 0;
$failed      = 0;

foreach ($items as $index => $item) {

    // Validate each item has 'technology' key
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

    // Check if already exists
    $fetchTechnology = mysqli_query(
        $con,
        "SELECT id FROM tbl_technology WHERE technology = '$technologyName' AND status = 1"
    );

    if (mysqli_num_rows($fetchTechnology) > 0) {
        $results[] = [
            'index'      => $index,
            'technology' => $technologyName,
            'status'     => false,
            'message'    => 'Technology already exists'
        ];
        $skipped++;
        continue;
    }

    // Insert new technology
    $insert = mysqli_query(
        $con,
        "INSERT INTO tbl_technology SET technology = '$technologyName', status = 1"
    );

    if ($insert) {
        // Log notification
        $text = "New Technology <" . $technologyName . "> added successfully";
        mysqli_query(
            $con,
            "INSERT INTO tbl_notification SET
                notify_to         = 'All',
                notification_type = 'info',
                notification_text = '$text',
                activity          = 'Masters'"
        );

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
            'message'    => 'Database insert failed'
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