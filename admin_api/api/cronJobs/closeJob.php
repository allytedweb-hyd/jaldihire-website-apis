<?php

include '../../includes/db-connection/db.php';

set_time_limit(10000);

$query = 'SELECT auto_create_jobid,no_of_positions FROM tbl_post_a_job LIMIT 20';

$queryVal = mysqli_query($con, $query);
$data = [];
while ($each = mysqli_fetch_assoc($queryVal)) {
    $JOB_ID = $each['auto_create_jobid'];

    $rows = mysqli_num_rows(mysqli_query($con, 'SELECT * FROM applicant_scheduled WHERE job_id="' . $JOB_ID . '" AND status=6'));

    if ($rows >= $each['no_of_positions']) {
        mysqli_query($con, 'UPDATE tbl_post_a_job SET job_status="closed",close_status=1,closed_by_role="Positions Filled"  WHERE auto_create_jobid="' . $JOB_ID . '"');
    }
}