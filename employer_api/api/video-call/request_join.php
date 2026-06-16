<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../includes/db-connection/db.php";

$schedule_id = $_GET['schedule_id'];
$applicant_peer_id = $_GET['applicant_peer_id'];

$query = "UPDATE video_calls SET applicant_peer_id = ?, status = 'pending' WHERE schedule_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $applicant_peer_id, $schedule_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Request sent']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send request']);
}
?>