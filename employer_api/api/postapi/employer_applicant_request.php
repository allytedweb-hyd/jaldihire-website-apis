<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'https://jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'https://jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

include '../../includes/db-connection/db.php';

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {

    $data = json_decode($json);
    $assign = $data->to;
    $assignedApplicantId = $data->assignedApplicantId;
    $assignedJobId = $data->assignedJobId;
    $requestedBy = $data->requestedId;
    $requestedRole = $data->requestedRole;
    $status = [];
    $message = '';
    $agentIds = [];
    $recruiterIds = [];
    $jobIds = [];
    $finalJobsList = [];
    $existStatus = [];
    $nonExistStatus = [];
    $closedStatus = [];

    if ($requestedRole == 'Applicant') {
        foreach ($assignedJobId as $item) {
            $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $item . "' && job_status='closed' && close_status=1");
            if (mysqli_num_rows($checkJobStatus) > 0) {
                $status = ['status' => false, 'message' => 'This Job is Closed'];
                exit(json_encode($status));
            }
        }
    }

    if ($assign == 'applicant') {
        foreach ($assignedJobId as $item) {
            $query = "SELECT * FROM assigned_jobs_list WHERE assigned_applicant_id='" . $assignedApplicantId . "' && assigned_job_id='" . $item . "' && (admin_status=1 OR admin_status=0) && (status=1 OR status=0)";
            $assignedjobs = mysqli_query($con, $query);
            $assignedjobsCount = mysqli_num_rows($assignedjobs);
            $jobDataone = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid= '" . $item . "' && status=1"));
            if ($assignedjobsCount == 0) {
                array_push($finalJobsList, $item);
                array_push($nonExistStatus, $jobDataone['auto_create_jobid']);
            } else {
                array_push($existStatus, $jobDataone['auto_create_jobid']);
            }
        }
    }

    // exit(json_encode($assignedJobId));

    if ($assign == 'applicant') {
        foreach ($assignedJobId as $jobId) {
            $getJobDetails = mysqli_query($con, "SELECT * FROM assign_job_recruiter T1, tbl_post_a_job T2 WHERE T2.auto_create_jobid = '" . $jobId . "' AND BINARY T1.assigned_job = BINARY T2.id ");
            $data = [];
            while ($row = mysqli_fetch_assoc($getJobDetails)) {
                $data = $row;
                $jobIds[] = $row['id'];
                $agentIds[] = $row['agent_id'];
                $recruiterIds[] = $row['assigned_reqruiter'];
            }
        }

        $getApplicantRecruiter = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_recruiter_assigns WHERE applicant_assigned_id='" . $assignedApplicantId . "' && status=1"));

        $jobAgents = [];
        foreach ($assignedJobId as $jobId) {
            $getAgentDetails = mysqli_query($con, "SELECT * FROM employer_agent_relation WHERE auto_create_jobid = '" . $jobId . "' ");
            $data = [];
            while ($row = mysqli_fetch_assoc($getAgentDetails)) {
                $data = $row;
                $jobAgents[] = $row['agent_id'];
            }
        }

        // exit (json_encode($getApplicantRecruiter['recruiter_assigned_id']));
        // exit (json_encode($getApplicantRecruiter));
        // exit (json_encode($recruiterIds));
        // exit (json_encode($agentIds));
        // && $recruiterIds != null this condition is removed from below for loop

        if (count($finalJobsList) > 0) {
            if ($getApplicantRecruiter != null && $jobAgents != null) {

                for ($i = 0; $i < count($finalJobsList); $i++) {

                    $myJobsQuery = "INSERT INTO assigned_jobs_list SET  assigned_applicant_id='" . $assignedApplicantId . "' ,assigned_job_id='" . $finalJobsList[$i] . "',assigned_agent='" . $jobAgents[$i] . "',assigned_recruiter='" . $getApplicantRecruiter['recruiter_assigned_id'] . "',requested_id='" . $requestedBy . "',requested_role='" . $requestedRole . "',admin_status=0,status=0";

                    $applicantmails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE status=1 && applicant_id = '" . $assignedApplicantId . "'"));

                    $agentdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM  tbl_allyted_team WHERE status=1 && allyted_member_id  = '" . $jobAgents[$i] . "'"));

                    $Jobdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && auto_create_jobid= '" . $finalJobsList[$i] . "'"));

                    $inserted = mysqli_query($con, $myJobsQuery);
                    if ($inserted == true) {
                        if ($existStatus) {
                            $message .= " Already Applied to " . implode(',', $existStatus);
                        }
                        if (count($nonExistStatus) > 0) {
                            $message .= " Applied to to " . implode(',', $nonExistStatus);
                        }
                        $status = ['status' => true, 'message' => $message];
                    } else {
                        $status = ['status' => false, 'message' => 'Failed to Assign'];
                    }
                }
            } else if ($agentIds == null) {
                if ($jobAgents != null) {
                    for ($i = 0; $i < count($finalJobsList); $i++) {

                        $myJobsQuery = "INSERT INTO assigned_jobs_list SET  assigned_applicant_id='" . $assignedApplicantId . "' ,assigned_job_id='" . $finalJobsList[$i] . "',assigned_agent='" . $jobAgents[$i] . "',requested_id='" . $requestedBy . "',requested_role='" . $requestedRole . "',admin_status=0,status=0";

                        $applicantmails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE status=1 && applicant_id = '" . $assignedApplicantId . "'"));

                        $agentdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM  tbl_allyted_team WHERE status=1 && allyted_member_id  = '" . $jobAgents[$i] . "'"));

                        $Jobdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && auto_create_jobid= '" . $finalJobsList[$i] . "'"));

                        $inserted = mysqli_query($con, $myJobsQuery);
                        if ($inserted == true) {
                            if ($existStatus) {
                                $message .= " Already Applied to " . implode(',', $existStatus);
                            }
                            if (count($nonExistStatus) > 0) {
                                $message .= " Applied to to " . implode(',', $nonExistStatus);
                            }
                            $status = ['status' => true, 'message' => $message];
                        } else {
                            $status = ['status' => false, 'message' => 'Failed to Assign'];
                        }
                    }
                } else {
                    $status = ['status' => false, 'message' => 'No Agents Are Assigned To Job'];
                }
            } else {
                $status = ['status' => false, 'message' => 'No recruiter Assigned'];
            }
            echo json_encode($status);
            exit();
        } else {
            $status = ['status' => false, 'message' => 'You Are Already Applied To The Job'];
            echo json_encode($status);
            exit();
        }

        // if (count($finalJobsList) > 0) {
        //     for ($i = 0; $i < count($finalJobsList); $i++) {

        //         $myJobsQuery = "INSERT INTO assigned_jobs_list SET  assigned_applicant_id='" . $assignedApplicantId . "' ,assigned_job_id='" . $finalJobsList[$i] . "',assigned_agent='" . $agentIds[$i] . "',assigned_recruiter='" . $recruiterIds[$i] . "',requested_id='" . $requestedBy . "',requested_role='" . $requestedRole . "',admin_status=0,status=0";

        //         $applicantmails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE status=1 && applicant_id = '" . $assignedApplicantId . "'"));

        //         $agentdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM  tbl_allyted_team WHERE status=1 && allyted_member_id  = '" . $agentIds[$i] . "'"));

        //         $Jobdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && auto_create_jobid= '" . $finalJobsList[$i] . "'"));

        //         $inserted = mysqli_query($con, $myJobsQuery);
        //         if ($inserted == true) {
        //             if ($existStatus) {
        //                 $message .= " Already Assigned to " . implode(',', $existStatus);
        //             }
        //             if (count($nonExistStatus) > 0) {
        //                 $message .= " Assigned to " . implode(',', $nonExistStatus);
        //             }
        //             $status = ['status' => true, 'message' => $message];
        //         } else {
        //             $status = ['status' => false, 'message' => 'Failed to Assign'];
        //         }
        //     }
        // } else {
        //     $status = ['status' => false, 'message' => 'Jobs Are Already Assigned'];
        // }
    } else {
        $status = ['status' => false, 'message' => 'Already Members are Assigned'];
    }
} else {
    $status = ['status' => false, 'message' => 'Something went worng please try again'];
}

echo json_encode($status);
