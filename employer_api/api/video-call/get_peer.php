<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include "includes/db.php";
include "../../includes/db-connection/db.php";

$schedule_id = $_GET['schedule_id'];

$query = "SELECT interviewer_peer_id FROM video_calls WHERE schedule_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'Peer ID not found']);
}
?>
