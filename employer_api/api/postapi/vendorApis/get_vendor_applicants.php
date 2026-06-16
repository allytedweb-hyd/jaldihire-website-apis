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

try {
    include "../../../includes/db-connection/db.php";

    if (!$con) {
        throw new Exception("Database connection failed");
    }

    // Verify vendorId input
    if (!isset($_GET['vendorId']) || empty($_GET['vendorId'])) {
        throw new Exception("Vendor ID is required");
    }

    $vendorId = $_GET['vendorId'];

    // Use a prepared statement to prevent SQL injection
    $query = "SELECT * FROM `tbl_applicant_add` WHERE `vendor_id` = ?";

    $stmt = $con->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare query: " . $con->error);
    }

    $stmt->bind_param("s", $vendorId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $applicants = [];
    while ($row = $result->fetch_assoc()) {
        $applicants[] = $row;
    }

    if (empty($applicants)) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'No applicants found for the given Vendor ID'
        ]);
        exit();
    }

    // Success response
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Applicants retrieved successfully',
        'data' => $applicants
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($con)) {
        $con->close();
    }
}
