<?php

include '../../includes/db-connection/db.php';

set_time_limit(10000);

$query = 'SELECT auto_create_jobid FROM tbl_post_a_job WHERE job_status="New" AND TIMESTAMPDIFF(day,created_date,CURRENT_TIMESTAMP()) > 0 LIMIT 20';

$queryVal = mysqli_query($con, $query);
$data = [];
while ($each = mysqli_fetch_assoc($queryVal)) {
    $JOB_ID = $each['auto_create_jobid'];
    $rows = mysqli_num_rows(mysqli_query($con, 'SELECT * FROM assigned_jobs_list WHERE assigned_job_id="'.$JOB_ID.'"'));
    if($rows > 0){
        mysqli_query($con, 'UPDATE tbl_post_a_job SET job_status="progress"  WHERE auto_create_jobid="' . $JOB_ID . '"');
    }else{
        mysqli_query($con, 'UPDATE tbl_post_a_job SET job_status="overdue"  WHERE auto_create_jobid="' . $JOB_ID . '"');
    }
}