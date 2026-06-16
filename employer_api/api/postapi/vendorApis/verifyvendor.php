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

require_once "../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    include "../../../includes/db-connection/db.php";

    if (!$con) {
        throw new Exception("Database connection failed");
    }

    // Get Authorization header
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        throw new Exception("Authorization token not found");
    }

    $token = $matches[1];
    $key = "J@ldiHire@321";

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        // Get vendor ID from token (support both user_id and vendor_id)
        $tokenVendorId = $decoded->user_id ?? $decoded->vendor_id ?? null;
        if (!$tokenVendorId) {
            throw new Exception("Token doesn't contain vendor identification");
        }

        // Get vendor ID from request (support both vendor_id and user_id)
        $requestVendorId = $_GET['vendor_id'] ?? $_GET['user_id'] ?? '';
        if ((string)$tokenVendorId !== (string)$requestVendorId) {
            throw new Exception("Token vendor identification doesn't match requested vendor");
        }

        // Verify vendor exists
        $stmt = $con->prepare("SELECT * FROM vendors WHERE vendor_id = ? AND status = 1");
        $stmt->bind_param("s", $tokenVendorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $vendorData = $result->fetch_assoc();

        if ($result->num_rows === 0) {
            throw new Exception("Vendor not found or inactive");
        }

        http_response_code(200);
        echo json_encode([
            'status' => true,
            'message' => 'Token is valid',
            'vendor_id' => $tokenVendorId,
            'vendor_data' => $vendorData
        ]);
    } catch (Exception $e) {
        throw new Exception("Token verification failed: " . $e->getMessage());
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        'status' => false,
        'message' => 'Authentication failed',
        'error' => $e->getMessage()
    ]);
}

if (isset($con)) {
    $con->close();
}
