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

// Start a try-catch block for centralized error handling
try {
    // Include the database connection file
    include "../../../includes/db-connection/db.php";

    // Check if the database connection is successful
    if (!$con) {
        throw new Exception("Database connection failed", 500);
    }

    // Get the JSON data from the request body
    $json = file_get_contents('php://input');
    if (empty($json)) {
        throw new Exception("No data received", 400);
    }

    // Decode the JSON data
    $data = json_decode($json);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON data: " . json_last_error_msg(), 400);
    }

    // --- Define Paths for Uploads and Database Storage ---
    // This is the local path where files will be saved on your server
    $uploadDir = "../vendor-uploads/applicant-images/";
    // This is the public URL that will be stored in the database
    $uploadUrl = "https://mmworkspace.com/jaldhihireadmin/api/post-apis/uploads/applicant-images/";

    // Create the upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception("Failed to create upload directory", 500);
        }
    }

    // Get the next applicant ID for auto-generation
    $result = mysqli_query($con, "SELECT applicant_id FROM `tbl_applicant_add` ORDER BY applicant_id DESC LIMIT 1");
    if (!$result) {
        throw new Exception("Failed to get applicant count: " . mysqli_error($con), 500);
    }

    $incCount = 1;
    if ($row = mysqli_fetch_assoc($result)) {
        $incCount = $row['applicant_id'] + 1;
    }

    // Generate a unique applicant ID
    $applicantName = $data->applicantName ?? 'APPLICANT';
    $autoCreateId = 'JDAPP' . strtoupper(substr(str_replace(' ', '', $applicantName), 0, 4)) . $incCount;

    // --- Handle File Uploads (Aadhaar & Resume) ---
    $fullAadhaarUrl = '';
    $fullResumeUrl = '';

    // Process Aadhaar file if provided
    if (!empty($data->aadhaar)) {
        $image_parts = explode(";base64,", $data->aadhaar);
        if (count($image_parts) < 2) {
            throw new Exception("Invalid Aadhaar image format", 400);
        }

        $image_base64 = base64_decode($image_parts[1]);
        if ($image_base64 === false) {
            throw new Exception("Failed to decode Aadhaar image", 400);
        }

        $fileName = uniqid() . '.png';
        $filePath = $uploadDir . $fileName; // Use local path to save the file

        if (file_put_contents($filePath, $image_base64) === false) {
            throw new Exception("Failed to save Aadhaar image", 500);
        }
        $fullAadhaarUrl = $uploadUrl . $fileName; // Store the full URL
    }

    // Process Resume file if provided
    if (!empty($data->resume)) {
        $image_parts = explode(";base64,", $data->resume);
        if (count($image_parts) < 2) {
            throw new Exception("Invalid resume file format", 400);
        }

        $image_base64 = base64_decode($image_parts[1]);
        if ($image_base64 === false) {
            throw new Exception("Failed to decode resume file", 400);
        }

        // Determine file extension based on MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, $image_base64);
        finfo_close($finfo);

        if (strpos($mime, 'pdf') !== false) {
            $extension = 'pdf';
        } elseif (strpos($mime, 'word') !== false) {
            $extension = 'docx';
        } else {
            throw new Exception("Unsupported resume file format: " . $mime, 400);
        }

        $fileName = uniqid() . '.' . $extension;
        $filePath = $uploadDir . $fileName; // Use local path to save the file

        if (file_put_contents($filePath, $image_base64) === false) {
            throw new Exception("Failed to save resume file", 500);
        }
        $fullResumeUrl = $uploadUrl . $fileName; // Store the full URL
    }

    // --- Validate and Sanitize Input Data ---
    $applicantName = $data->applicantName ?? '';
    $clientName = $data->clientName ?? '';
    $createDate = $data->createDate ?? '';
    $currentCTC = $data->currentCTC ?? '';
    $email = $data->email ?? '';
    $expectedCTC = $data->expectedCTC ?? '';
    $total_expereience = $data->experience ?? '';
    $noticePeriod = $data->noticePeriod ?? '';
    $phone = $data->phone ?? '';
    $relExp = $data->relExp ?? '';
    $main_skill = $data->skill ?? '';
    $spocName = $data->spocName ?? '';
    $vendorId = $data->vendorId ?? '';

    // --- Prepare and Execute SQL Insert Query ---
    $query = "INSERT INTO `tbl_applicant_add` SET 
        `full_name` = ?,
        `applicant_auto_create_id` = ?,
        `mobile_number` = ?,
        `e_mail` = ?,
        `aadhar_number` = ?,
        `current_package` = ?,
        `expected_package` = ?,
        `total_expereience` = ?,
        `relevant_exp` = ?,
        `notice_period` = ?,
        `main_skill` =?,
        `spocName` = ?,
        `resume_image` = ?,
        `vendor_id` = ?,
        `client_id` = ?,
        `status` = 1";

    $stmt = $con->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare query: " . $con->error, 500);
    }

    $stmt->bind_param(
        "sssssssssssssss",
        $applicantName,
        $autoCreateId,
        $phone,
        $email,
        $fullAadhaarUrl,
        $currentCTC,
        $expectedCTC,
        $total_expereience,
        $relExp,
        $noticePeriod,
        $main_skill,
        $spocName,
        $fullResumeUrl,
        $vendorId,
        $clientName
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to add applicant: " . $stmt->error, 500);
    }

    // --- Success Response ---
    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'message' => 'Applicant added successfully',
        'applicant_id' => $autoCreateId,
        'aadhaar_url' => $fullAadhaarUrl,
        'resume_url' => $fullResumeUrl
    ]);
} catch (Exception $e) {
    // --- Error Response ---
    $statusCode = $e->getCode() > 0 ? $e->getCode() : 400;
    http_response_code($statusCode);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    // Ensure the database connection is closed
    if (isset($con)) {
        $con->close();
    }
}
