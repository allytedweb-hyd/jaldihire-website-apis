<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../includes/db-connection/db.php";

$schedule_id = $_GET['schedule_id'];

$query = "UPDATE video_calls SET status = 'ended' WHERE schedule_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $schedule_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Call ended']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to end call']);
}
?>