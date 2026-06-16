<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include "includes/db.php";
include "../../includes/db-connection/db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $action = $_GET['action'];

    $schedule_id = $_POST['schedule_id'];
    $peer_id = $_POST['peer_id'];

    if ($action == "request") {
        $query = "INSERT INTO video_calls (schedule_id, peer_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $schedule_id, $peer_id);
        $stmt->execute();

        echo json_encode(["message" => "Call request sent"]);
    } elseif ($action == "accept") {
        $query = "UPDATE video_calls SET status = 'accepted' WHERE schedule_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $schedule_id);
        $stmt->execute();

        echo json_encode(["message" => "Call accepted"]);
    }
} elseif ($method == "GET") {
    $schedule_id = $_GET['schedule_id'];

    $query = "SELECT * FROM video_calls WHERE schedule_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $call = $result->fetch_assoc();

    echo json_encode($call ? $call : ["message" => "No call request found"]);
}
?>