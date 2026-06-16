<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include "includes/db.php";
include "../../includes/db-connection/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['meeting_id'])) {
    $meeting_id = $_GET['meeting_id'];
    $result = $con->query("SELECT * FROM applicant_scheduled WHERE roomid = '$meeting_id'");

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['status' => false, 'message' => 'Meeting not found']);
    }
    $con->close();
}
// echo json_encode($data);

?>