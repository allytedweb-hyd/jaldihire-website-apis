<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin");
include "../../includes/db-connection/db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $checkTeam = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_add_team WHERE interview_round IN ('" . $id . "') && status=1"));

    if ($checkTeam > 0) {
        $stat = ['status' => false, 'message' => 'Team Member Created With This Round'];
        exit(json_encode($stat));
    }

    $checkInterview = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE 
    (round IN ('" . $id . "') OR assigned_rounds IN ('" . $id . "'))"));

    if ($checkInterview > 0) {
        $stat = ['status' => false, 'message' => 'Interview Scheduled With This Round'];
        exit(json_encode($stat));
    }

    $delete = mysqli_query($con, "UPDATE tbl_interview_rounds SET status='0' WHERE id='" . $id . "'");
} else if (isset($_GET['companyID']) && !empty($_GET['companyID'])) {
    $empId = $_GET['companyID'];

    $q = "UPDATE tbl_interview_rounds SET status=0 WHERE emp_id='" . $empId . "' AND id NOT IN (SELECT interview_round FROM tbl_add_team WHERE status=1) AND id NOT IN (SELECT assigned_rounds FROM applicant_scheduled)";

    $delete = mysqli_query($con, $q);
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
    exit(json_encode($stat));
}

if ($delete == true) {
    $stat = ['status' => true, 'message' => 'Deleted Successfully'];
} else {
    $stat = ['status' => true, 'message' => 'Failed to Delete'];
}

echo json_encode($stat);