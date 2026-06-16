<?php
include "includes/db-connection/db.php";

// Check connection
if ($con->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit();
}

$sql = "INSERT INTO download_pdf (pdf_mail) VALUES ('$email')";
if ($con->query($sql) === TRUE) {
    $file = 'images/files/Recruiter_Performance_Optimization_Guide (1).pptx';  // Path to your file

    if (file_exists($file)) {
        $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        switch ($file_extension) {
            case 'pdf':
                $mime_type = 'application/pdf';
                break;
            case 'ppt':
                $mime_type = 'application/vnd.ms-powerpoint';
                break;
            case 'pptx':
                $mime_type = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                break;
            case 'doc':
                $mime_type = 'application/msword';
                break;
            case 'docx':
                $mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            default:
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Unsupported file type']);
                exit();
        }

        echo json_encode(['status' => 'success', 'message' => 'File downloaded successfully', 'file' => basename($file)]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'File not found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $con->error]);
}

$con->close();
?>
