<?php
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');

include "../../includes/db-connection/db.php";
include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();


// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);

TokenVerify($token);

if (isset($_GET['dlt']) && !empty($_GET['dlt'])) {
    $deleteId = $_GET['dlt'];

    // $delete_agent = mysqli_query($con, "UPDATE tbl_allyted_team SET status='0' WHERE allyted_member_id ='" . $deleteId . "'");


    // if ($delete_agent == true) {

    $deleteAgentAssignEmployer = mysqli_query($con, "UPDATE assigned_Recruiters_list SET status=0 WHERE assigned_recruiter_id='" . $deleteId . "'");

    $deleteAgentAssignJobs = mysqli_query($con, "UPDATE employer_agent_relation SET status=0 WHERE agent_id='" . $deleteId . "'");

    if ($deleteAgentAssignEmployer == true && $deleteAgentAssignJobs == true) {
        $stat = ['status' => true, 'message' => 'Team Deleted Successfully'];
    } else {
        $stat = ['status' => false, 'message' => 'Failed to Delete Jobs'];
    }
    // } else {
    //     $stat = ['status' => false, 'message' => 'Failed to Delete'];
    // }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($stat);

?>