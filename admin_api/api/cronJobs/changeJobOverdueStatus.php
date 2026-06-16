<?php

include '../../includes/db-connection/db.php';

set_time_limit(10000);

$query = "SELECT auto_create_jobid,created_date FROM tbl_post_a_job WHERE job_status='progress' AND DATEDIFF(CURRENT_DATE(),cron_last_update) >= 1 LIMIT 200";

$queryVal = mysqli_query($con, $query);
$data = [];
while ($each = mysqli_fetch_assoc($queryVal)) {
    $data[] = $each;
}

foreach ($data as $key => $value) {
    $isExists = mysqli_fetch_assoc(mysqli_query($con, "SELECT updated_on FROM assigned_jobs_list WHERE assigned_job_id='{$value['auto_create_jobid']}' ORDER BY updated_on DESC"));

    if ($isExists == null) {
        // Calculations for the date
        $jobDate = date('Y-m-d', strtotime($value['created_date']));
        $cuurDate = date('Y-m-d');
        $date1 = date_create($jobDate);
        $date2 = date_create($cuurDate);
        $diff = date_diff($date1, $date2);
        $totalDifferenceDays = $diff->days;

        // check the no of days

        if ($totalDifferenceDays >= 5) {
            mysqli_query($con, "UPDATE tbl_post_a_job SET job_status='overdue',over_due_days='{$totalDifferenceDays}' WHERE auto_create_jobid='{$value['auto_create_jobid']}'");
        }
    } else {
        $jobDate = date('Y-m-d', strtotime($isExists['updated_on']));
        $cuurDate = date('Y-m-d');
        $date1 = date_create($jobDate);
        $date2 = date_create($cuurDate);
        $diff = date_diff($date1, $date2);
        $totalDifferenceDays = $diff->days;

        if ($totalDifferenceDays >= 5) {
            mysqli_query($con, "UPDATE tbl_post_a_job SET job_status='overdue',over_due_days='{$totalDifferenceDays}' WHERE auto_create_jobid='{$value['auto_create_jobid']}'");
        }
    }

    mysqli_query($con, "UPDATE tbl_post_a_job SET cron_last_update=CURRENT_DATE() WHERE auto_create_jobid='{$value['auto_create_jobid']}'");
}