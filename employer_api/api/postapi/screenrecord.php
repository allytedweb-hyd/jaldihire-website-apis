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

include "../../includes/db-connection/db.php"; // Database connection

$folderPath = "./uploads/";

if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["video"])) {
    $fileName = "recording_" . time() . "_" . uniqid() . ".webm";
    $uploadPath = $folderPath . $fileName;

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $uploadPath)) {
        $fileUrl = $uploadPath;

        // Store in database
        $stmt = $conn->prepare("INSERT INTO tbl_resource_managers (file_name, file_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $fileName, $fileUrl);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "fileUrl" => $fileUrl]);
        } else {
            echo json_encode(["error" => "Database insert failed"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Upload failed"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}

$conn->close();
