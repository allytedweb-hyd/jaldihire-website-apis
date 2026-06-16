<?php
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
include "../../includes/db-connection/db.php";
include "../libs/mail_function.php";

$json = file_get_contents('php://input');

include '../libs/mails.php';

include "../libs/jwt.php";

$data = json_decode($json);

if (isset($json) && !empty($json)) {

    @$status = $data->status;
    @$id = $data->id;
    @$finalstatus = $data->finalstatus;
    @$remarks = $data->remarks;
    @$job_id = $data->job_id;
    @$round = $data->round;
    @$substatus = $data->Substatus;
    @$roundnew = $data->round;
    @$interviwerround = $data->interviewer;
    @$interViewername = $data->interviwername;
    @$scheduled_date = $data->date;
    @$currentround = $data->currentround;
    @$strengths = $data->strengths;
    @$weaknesses = $data->weaknesses;
    @$conclusion = $data->conclusion;
    @$note = $data->note;
    @$job_id_new = $data->job_id;

    $clause = '';

    @$skill_form = [];
    if (isset($data->skill_form)) {
        $skill_form = $data->skill_form;
    }
    $getEmpId = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $job_id . "'"));
    $employer_id = $getEmpId['company_Id'];

    // ? to check interviews count in employer wallet
    $checkInterviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT Onboardings ,Interviews FROM tbl_add_employer WHERE employer_auto_create_id='" . $employer_id . "'"));

    if (!intval($checkInterviews['Interviews']) > 0) {
        exit(json_encode(['status' => false, 'message' => 'You Do Not have enough interviews in your wallet']));
    }

    if ($finalstatus == 6) {
        $clause = "OnBoardingDate='" . $data->OnBoardingDate . "',hired_package='" . $data->SalaryPackage . "',";

        if (!intval($checkInterviews['Onboardings']) > 0) {
            exit(json_encode(['status' => false, 'message' => 'You Do Not have enough On Boardings in your wallet']));
        }
    }

    // ? to check Job Status and stop if job is closed
    $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $job_id . "' && job_status='closed' && close_status=1");
    if (mysqli_num_rows($checkJobStatus) > 0) {
        $status = ['status' => false, 'message' => 'This Job is Closed'];
        exit(json_encode($status));
    }

    $sel = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE schedule_id ='" . $id . "'"));
    $CurrentRound = $sel['round'];
    $AppId = $sel['applicant_id'];


    $scheduleStatus = mysqli_query($con, "UPDATE applicant_scheduled SET $clause current_round_status='" . $status . "',remarks='" . $remarks . "',status='" . $finalstatus . "',subStatus='" . $substatus . "' WHERE schedule_id ='" . $id . "'");

    if ($scheduleStatus == true) {

        if ($data->schedule_id == '' && $data->schedule_id == NULL) {
            for ($i = 0; $i < count($skill_form); $i++) {
                $skill_name[$i] = $skill_form[$i]->skill_name;
                $skill_id[$i] = $skill_form[$i]->skill_id;
                $rating[$i] = $skill_form[$i]->rating;

                $insert_skills = mysqli_query($con, query: "INSERT INTO applicant_scheduled_skill SET 
            applicant_id='" . $AppId . "',
            skill_name='" . $skill_name[$i] . "',
            skill_rating='" . $rating[$i] . "',
            app_skill_Id	='" . $skill_id[$i] . "',
            round_no='" . $CurrentRound . "',
            schedule_id='" . $id . "',
            status=1");
            }

            $insertroundrecord = mysqli_query($con, 'INSERT INTO applicant_scheduled_data SET schedule_id="' . $id . '",applicant_id="' . $AppId . '",assigned_round="' . $CurrentRound . '",strengths="' . $strengths . '",weakness="' . $weaknesses . '",job_Id="' . $job_id_new . '",note="' . $note . '",conclusion="' . $conclusion . '",current_round="' . $currentround . '",final_status="' . $finalstatus . '",sub_status="' . $substatus . '",interviwer_name="' . $interViewername . '",status=1');
        }

        $query = "UPDATE interview_record SET current_round_status='1',remarks='" . $remarks . "',interview_status='" . $finalstatus . "',subStatus='" . $substatus . "' WHERE schedule_id ='" . $id . "' AND round ='" . $CurrentRound . "'";
        $updateInterviewRecord = mysqli_query($con, $query);

        $getMailsData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE schedule_id ='" . $id . "'"));

        if ($finalstatus == 6) {
            mysqli_query($con, "UPDATE tbl_applicant_add SET on_boarded=1 WHERE applicant_id='" . $getMailsData['applicant_id'] . "' AND status=1");
        }

        $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT a.e_mail as email,a.full_name as applicant_name FROM tbl_applicant_add a,applicant_scheduled b WHERE a.applicant_id = b.applicant_id and b.schedule_id ='" . $id . "'"));

        $id = $getDetails['email'];

        $Subject = "Interview Update";

        $recruiterBody = " Hello, " . $getMailsData['job_recruitername'] . " interviewer update your interview status to $finalstatus  ";
        $applicantBody = " Hello, " . $getDetails['applicant_name'] . " interviewer update your interview status to $finalstatus  ";

        recruiter([$getMailsData['jobrecruitermail'], $getMailsData['recruiter_mail']], $Subject, $recruiterBody);

        scheduleApplicant($id, $Subject, $applicantBody);


        if (isset($data->schedule_id) && !empty($data->schedule_id)) {
            $schedule_id = $data->schedule_id;
            $whereClause = [];

            $getScheduledInterviewDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE schedule_id ='{$schedule_id}'"));
            $assignedround = $getScheduledInterviewDetails['round'];
            $AppId = $getScheduledInterviewDetails['applicant_id'];
            if (isset($data->round) && !empty($data->round)) {
                $updatedRounds = $getScheduledInterviewDetails['assigned_rounds'] . $data->round . ',';
                array_push($whereClause, "round='{$data->round}'");
                array_push($whereClause, "interviewer_id='" . $data->interviewer . "'");
                array_push($whereClause, "assigned_rounds='{$updatedRounds}'");
                array_push($whereClause, "current_round_status=0");
            }

            if (isset($data->current_round_status) && !empty($data->current_round_status)) {
                array_push($whereClause, "current_round_status='{$data->current_round_status}'");
            }

            if (isset($data->epoch_date) && !empty($data->epoch_date)) {
                array_push($whereClause, "epoch_date='{$data->epoch_date}'");
            }

            if (isset($data->date) && !empty($data->date)) {
                array_push($whereClause, "scheduled_date='{$data->date}'");
            }

            if (isset($data->status) && !empty($data->status)) {
                array_push($whereClause, "status='{$data->status}'");
            }

            $arrayClause = '';


            if (count($whereClause) > 0) {
                $arrayClause = implode(",", $whereClause);
            } else {
                $status = ['status' => false, 'message' => 'please submit any data to update'];
                echo json_encode($status);
                exit();
            }

            $query = "UPDATE applicant_scheduled SET $arrayClause WHERE schedule_id='{$schedule_id}'";

            $scheduledapplicant = mysqli_query($con, $query);

            if ($scheduledapplicant == true) {

                for ($i = 0; $i < count($skill_form); $i++) {
                    $skill_name[$i] = $skill_form[$i]->skill_name;
                    $skill_id[$i] = $skill_form[$i]->skill_id;
                    $rating[$i] = $skill_form[$i]->rating;

                    $insert_skills = mysqli_query($con, "INSERT INTO applicant_scheduled_skill SET 
                    applicant_id='" . $AppId . "',
                    skill_name='" . $skill_name[$i] . "',
                    skill_rating='" . $rating[$i] . "',
                    app_skill_Id	='" . $skill_id[$i] . "',
                    round_no='" . $CurrentRound . "',
                    schedule_id='" . $schedule_id . "',
                    status=1");
                }

                $insertroundrecord = mysqli_query($con, 'INSERT INTO applicant_scheduled_data SET schedule_id="' . $schedule_id . '",applicant_id="' . $AppId . '",interviwer_id="' . $interviwerround . '",assigned_round="' . $CurrentRound . '",strengths="' . $strengths . '",weakness="' . $weaknesses . '",job_Id="' . $job_id_new . '",note="' . $note . '",conclusion="' . $conclusion . '",current_round="' . $currentround . '",interviwer_name="' . $interViewername . '",scheduled_date="' . $scheduled_date . '",status=1');

                $createInterviewRounds = mysqli_query($con, "INSERT INTO interview_record SET 
                applicant_id='" . $getScheduledInterviewDetails['applicant_id'] . "',
                schedule_id='" . $schedule_id . "',
                employer_id='" . $getScheduledInterviewDetails['employer_id'] . "',
                interviewer_id='" . $data->interviewer . "',
                job_id='" . $getScheduledInterviewDetails['job_id'] . "',
                agent_id='" . $getScheduledInterviewDetails['recruiter_auto_id'] . "',
                agent_name='" . $getScheduledInterviewDetails['recruiter_name'] . "',
                agent_mail='" . $getScheduledInterviewDetails['recruiter_mail'] . "',
                recruiter_id='" . $getScheduledInterviewDetails['jobrecruiterid'] . "',
                recruiter_name='" . $getScheduledInterviewDetails['job_recruitername'] . "',
                recruiter_mail='" . $getScheduledInterviewDetails['jobrecruitermail'] . "',
                scheduled_date='" . $data->date . "',
                text='" . $getScheduledInterviewDetails['text'] . "',
                employer_name='" . $getScheduledInterviewDetails['employer_name'] . "',
                employer_mail='" . $getScheduledInterviewDetails['employer_mail'] . "',
                round='" . $data->round . "',
                interview_status=0");

                if ($createInterviewRounds) {
                    $Subject = "Interview Process forward to Next Video ";
                    $applicantBody = " Hello, " . $getDetails['applicant_name'] . " you are forward to  ";
                    $recruiterBody = " Hello, " . $getMailsData['job_recruitername'] . " interviewer update your interview status to $finalstatus  ";

                    recruiter([$getMailsData['jobrecruitermail'], $getMailsData['recruiter_mail']], $Subject, $recruiterBody);

                    scheduleApplicant($id, $Subject, $applicantBody);
                    $stat = ['status' => true, 'message' => 'Scheduled Successfully'];
                } else {
                    $stat = ['status' => false, 'message' => 'Failed'];
                }
            } else {
                $stat = ['status' => false, 'message' => 'Failed! Please Try Again'];
            }
        } else {
            if ($updateInterviewRecord) {
                $stat = ['status' => true, 'message' => 'Success'];
            } else {
                $stat = ['status' => false, 'message' => 'Failed'];
            }
        }
    } else {
        $stat = ['status' => false, 'message' => 'failed'];
    }
} else {
    $stat = ['status' => false, 'Message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
