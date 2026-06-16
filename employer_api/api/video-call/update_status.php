<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../includes/db-connection/db.php";

$schedule_id = $_GET['schedule_id'];
$status = $_GET['status']; // accepted or rejected

$query = "UPDATE video_calls SET status = ? WHERE schedule_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $status, $schedule_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Status updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>