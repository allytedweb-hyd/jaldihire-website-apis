<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include "includes/db.php";
include "../../includes/db-connection/db.php";

$schedule_id = $_GET['schedule_id'];
$peer_id = $_GET['peer_id'];

if (!$schedule_id || !$peer_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

// Check if an interview record already exists
$query = "SELECT * FROM video_calls WHERE schedule_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing record
    $update = "UPDATE video_calls SET interviewer_peer_id = ? WHERE schedule_id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ss", $peer_id, $schedule_id);
} else {
    // Insert new record
    $insert = "INSERT INTO video_calls (schedule_id, interviewer_peer_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("ss", $schedule_id, $peer_id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Peer ID saved']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>