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

require_once "../../../includes/db-connection/db.php";

try {
    if (!$con) {
        throw new Exception("Database connection failed");
    }

    // Get the JSON data from the POST request
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // Validate required fields
    if (!isset($data['vendor_id']) || empty($data['vendor_id'])) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Vendor ID is required for update.']);
        exit();
    }

    // Extract data from the JSON payload
    $vendor_id = $data['vendor_id'];
    $vendor_name = $data['vendor_name'] ?? null;
    $spoc_name = $data['spoc_name'] ?? null;
    $spoc_email = $data['spoc_email'] ?? null;
    $spoc_phone = $data['spoc_mobile'] ?? null; // Ensure this matches your form key
    $vendor_status = $data['status'] ?? null; // Ensure this matches your form key
    $reason = $data['reason'] ?? null;

    // Prepare the UPDATE SQL statement
    $stmt = $con->prepare("
        UPDATE vendors 
        SET 
            vendor_name = ?,
            spoc_name = ?, 
            spoc_email = ?,
            spoc_phone = ?,
            vendor_status = ?,
            reason = ?
        WHERE vendor_id = ?
    ");

    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $con->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssi", // s for string, i for integer (assuming vendor_id is an integer)
        $vendor_name,
        $spoc_name,
        $spoc_email,
        $spoc_phone,
        $vendor_status,
        $reason,
        $vendor_id
    );

    // Execute the statement
    $success = $stmt->execute();

    if ($success) {
        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'Vendor updated successfully']);
        } else {
            http_response_code(200);
            echo json_encode(['status' => false, 'message' => 'No changes were made to the vendor record.']);
        }
    } else {
        throw new Exception("Update failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()]);
}

if (isset($con)) {
    $con->close();
}
