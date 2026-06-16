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

include "../../includes/db-connection/db.php";
$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {
    $data = json_decode($json);

    $status = $data->status;
    $id = $data->id;
    $finalstatus = $data->finalstatus;
    $remarks = $data->remarks;
    $job_id = $data->job_id;
    $substatus = $data->Substatus;
    $roundnew = $data->round;
    $interviwerround = $data->interviewer;
    $interViewername = $data->interviewname;
    $scheduled_date = $data->date;
    $currentround = $data->currentround;
    $conclusion = $data->conclusion;
    $note = $data->note;
    $strengths = $data->strengths;
    $weaknesses = $data->weaknesses;
    $job_id_new = $data->job_id;
    $clause = '';
    @$skill_form = [];
    if (isset($data->skill_form)) {
        $skill_form = $data->skill_form;
    }

    if ($finalstatus == 6) {
        $clause = "OnBoardingDate='" . $data->OnBoardingDate . "',hired_package='" . $data->SalaryPackage . "',";
    }

    if (!isset($data->schedule_id) && empty($data->schedule_id)) {
        $status = ['status' => false, 'message' => 'Invalid Params'];
        echo json_encode($status);
        exit();
    }

    // ? to check Job Status and stop if job is closed

    $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $data->job_id . "' && job_status='closed' && close_status=1");

    if (mysqli_num_rows($checkJobStatus) > 0) {
        $status = ['status' => false, 'message' => 'This Job is Closed'];
        exit(json_encode($status));
    }

    $schedule_id = $data->schedule_id;
    $whereClause = [];

    $getScheduledInterviewDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE schedule_id ='{$schedule_id}'"));
    $CurrentRound = $getScheduledInterviewDetails['round'];
    $AppId = $getScheduledInterviewDetails['applicant_id'];
    $AppId = $getScheduledInterviewDetails['applicant_id'];
    $scheduleddateinterview = $getScheduledInterviewDetails['scheduled_date'];



    if (isset($data->finalstatus) && !empty($data->finalstatus)) {
        array_push($whereClause, "status='{$data->finalstatus}'");
        $data->finalstatus == 5 ? array_push($whereClause, "current_round_status=3") : array_push($whereClause, "current_round_status=2");
    } else {
        array_push($whereClause, "status=0");
        array_push($whereClause, "current_round_status=1");
    }

    if (isset($data->Substatus) && !empty($data->Substatus)) {
        array_push($whereClause, "subStatus='{$data->Substatus}'");
    }

    if (isset($data->remarks) && !empty($data->remarks)) {
        array_push($whereClause, "remarks='{$data->remarks}'");
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

    if ($scheduledapplicant) {
        if (!empty($data->finalstatus)) {
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
                schedule_id='" . $id . "',
                status=1");
            }

            $insertroundrecord = mysqli_query($con, 'INSERT INTO applicant_scheduled_data SET schedule_id="' . $id . '",applicant_id="' . $AppId . '",assigned_round="' . $CurrentRound . '",strengths="' . $strengths . '",weakness="' . $weaknesses . '",job_Id="' . $job_id_new . '",note="' . $note . '",conclusion="' . $conclusion . '",current_round="' . $currentround . '",interviwer_name="' . $interViewername . '",scheduled_date="' . $scheduleddateinterview . '",final_status="' . $finalstatus . '",sub_status="' . $substatus . '",status=1');
        } else {
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
                schedule_id='" . $id . "',
                status=1");
            }

            $insertroundrecord = mysqli_query($con, 'INSERT INTO applicant_scheduled_data SET schedule_id="' . $id . '",applicant_id="' . $AppId . '",interviwer_id="' . $interviwerround . '",assigned_round="' . $CurrentRound . '",strengths="' . $strengths . '",weakness="' . $weaknesses . '",job_Id="' . $job_id_new . '",note="' . $note . '",conclusion="' . $conclusion . '",current_round="' . $currentround . '",interviwer_name="' . $interViewername . '",scheduled_date="' . $scheduleddateinterview . '",status=1');
        }


        $query = "UPDATE interview_record SET current_round_status='1',remarks='" . $remarks . "',interview_status='" . $finalstatus . "',subStatus='" . $substatus . "' WHERE schedule_id ='" . $id . "' AND round ='" . $CurrentRound . "'";

        try {
            $updateInterviewRecord = mysqli_query($con, $query);
            $stat = ['status' => true, 'message' => 'Interview Updated successfully'];
        } catch (Exception $e) {
            $stat = ['status' => false, 'error' => $e];
        }
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
