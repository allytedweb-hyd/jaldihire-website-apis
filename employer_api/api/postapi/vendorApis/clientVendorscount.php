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
    // Check for database connection
    if (!$con) {
        throw new Exception("Database connection failed");
    }

    // Get the employer_id from the GET request
    $employerId = $_GET['employer_id'] ?? null;

    if (!$employerId) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'status' => false,
            'message' => 'Employer ID is required'
        ]);
        exit();
    }

    // Prepare the SQL query to count vendors
    // We count vendors that match the employer_id and have a status of 1 (available)
    $stmt = $con->prepare("SELECT COUNT(*) FROM vendors WHERE employer_id = ? AND status = 1");
    $stmt->bind_param("s", $employerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        throw new Exception("Query failed: " . $con->error);
    }

    // Fetch the count from the result
    $row = $result->fetch_row();
    $vendorCount = $row[0];

    // Close the statement
    $stmt->close();

    // Set HTTP status code to 200 (OK) and return the JSON response
    http_response_code(200);
    echo json_encode([
        'status' => true,
        'message' => 'Vendor count retrieved successfully',
        'count' => $vendorCount
    ]);
} catch (Exception $e) {
    // Handle exceptions and return an error response
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => false,
        'message' => 'An error occurred',
        'error' => $e->getMessage()
    ]);
}

// Close the database connection
if (isset($con)) {
    $con->close();
}
