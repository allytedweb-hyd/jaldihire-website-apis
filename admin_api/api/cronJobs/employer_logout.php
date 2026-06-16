<?php

include '../../includes/db-connection/db.php';

set_time_limit(10000);

date_default_timezone_set('asia/kolkata');
$dateTime = date("Y-m-d H:i:s");


$query = 'SELECT login_time,employer_auto_create_id FROM tbl_add_employer WHERE is_logged_in=1 LIMIT 200';

$queryVal = mysqli_query($con, $query);

$data = [];

while ($each = mysqli_fetch_assoc($queryVal)) {

    $empId = $each['employer_auto_create_id'];
    $diff = date_diff(date_create($dateTime), date_create($each['login_time']));

    if ($diff->format("%a") >= 1) {

        mysqli_query($con, 'UPDATE tbl_add_employer SET is_logged_in=0 AND login_time="0000-00-00 00:00:00"  WHERE employer_auto_create_id="' . $empId . '"');

    }
}


$interviewers = "SELECT login_time,auto_create_id FROM tbl_add_team WHERE is_logged_in=1 LIMIT 200";

$Values = mysqli_query($con, $interviewers);

while ($int = mysqli_fetch_assoc($Values)) {

    $interviewerId = $int['auto_create_id'];
    $diff1 = date_diff(date_create($dateTime), date_create($int['login_time']));

    if ($diff1->format("%a") >= 1) {

        mysqli_query($con, "UPDATE tbl_add_team SET is_logged_in=0,login_time='0000-00-00 00:00:00' WHERE auto_create_id='" . $interviewerId . "'");

    }
}


echo json_encode($data);
