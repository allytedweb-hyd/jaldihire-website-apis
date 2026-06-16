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

    $employerId = $_GET['employer_id'] ?? null;
    if (!$employerId) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Employer ID is required']);
        exit();
    }

    $stmt = $con->prepare("SELECT * FROM vendors WHERE employer_id = ? ORDER BY created_on DESC");
    $stmt->bind_param("s", $employerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vendors = [];
        while ($row = $result->fetch_assoc()) {
            $vendors[] = $row;
        }
        http_response_code(200);
        echo json_encode(['status' => true, 'message' => 'Vendors retrieved successfully', 'data' => $vendors]);
    } else {
        http_response_code(200);
        echo json_encode(['status' => true, 'message' => 'No vendors found', 'data' => []]);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()]);
}

if (isset($con)) {
    $con->close();
}
