<?php

include '../../includes/db-connection/db.php';

function getAllowedNotifications($empId)
{
    global $con;
    try {
        $getAllowedNotifications = mysqli_fetch_assoc(mysqli_query($con, "SELECT employer_alerts,advisor_alerts,recruiter_alerts,admin_alerts,interview_alerts,job_alerts FROM allowed_notifications WHERE user_id='" . $empId . "'"));

        if ($getAllowedNotifications != null) {
            $stat = ['status' => true, 'data' => $getAllowedNotifications];
        } else {
            $stat = ['status' => false];
        }
    } catch (Exception $e) {
        $stat = ['status' => false, 'error' => $e];
    }
    return $stat;
}


function getAgentAllowedNotify($agentId)
{
    global $con;
    try {
        $getAllowedNotifications = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM allowed_notifications WHERE user_id='" . $agentId . "'"));

        if ($getAllowedNotifications != null) {
            $stat = ['status' => true, 'data' => $getAllowedNotifications];
        } else {
            $stat = ['status' => false];
        }
    } catch (Exception $e) {
        $stat = ['status' => false, 'error' => $e];
    }
    return $stat;
}
