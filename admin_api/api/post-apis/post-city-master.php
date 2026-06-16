<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json; charset=UTF-8");

try {

    // =========================
    // CORS SETTINGS
    // =========================
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

    // =========================
    // HANDLE OPTIONS REQUEST
    // =========================
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // =========================
    // INCLUDE FILES
    // =========================
    include "../../includes/db-connection/db.php";

    // Check DB Connection
    if (!$con) {
        throw new Exception("Database Connection Failed: " . mysqli_connect_error());
    }

    // =========================
    // GET RAW JSON
    // =========================
    $json = file_get_contents('php://input');

    if (!$json) {
        throw new Exception("No input data received");
    }

    // =========================
    // DECODE JSON
    // =========================
    $data = json_decode($json);

    // Check JSON Decode Error
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }

    // =========================
    // VALIDATE INPUTS
    // =========================
    if (!isset($data->state) || empty($data->state)) {
        throw new Exception("State is required");
    }

    if (!isset($data->city) || empty($data->city)) {
        throw new Exception("City is required");
    }

    $statename = mysqli_real_escape_string($con, trim($data->state));
    $cityname  = mysqli_real_escape_string($con, trim($data->city));

    // =========================
    // INSERT QUERY
    // =========================
    $query = "INSERT INTO tbl_city_master 
              SET state='$statename',
                  city_name='$cityname',
                  status=1";

    $insert_city = mysqli_query($con, $query);

    if (!$insert_city) {
        throw new Exception("MySQL Error: " . mysqli_error($con));
    }

    // =========================
    // SUCCESS RESPONSE
    // =========================
    echo json_encode([
        'status' => true,
        'message' => 'City inserted successfully'
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'error' => $e->getMessage()
    ]);
}
?>