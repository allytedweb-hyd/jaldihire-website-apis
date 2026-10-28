<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin");

require_once '../vendor/phpmailer/PHPMailerAutoload.php';
require_once '../vendor/autoload.php';
require_once 'mail_function.php';





// function initializePhpMailer()
// {
// global $mail;
$mail = new PHPMailer();
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->isSMTP();
$mail->Host = 'smtpout.secureserver.net';
$mail->SMTPAuth = true;
$mail->Username = 'info@allyted.com';
$mail->Password = 'Allyted@2026';
// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
return $mail;
// }


function scheduleApplicant($userEmail, $subject, $body)
{

    try {
        global $mail;
        $mail->setFrom('info@allyted.com', 'Jaldi Hire');


        $mail->addAddress($userEmail);

        $mail->addReplyTo('info@allyted.com', 'Information');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = applicantSchedule($body);
        $mail->send();
    } catch (Exception $e) {
    }
}

function recruiter($userEmail, $subject, $body)
{

    try {
        // $mail = initializePhpMailer();
        global $mail;

        $mail->setFrom('info@allyted.com', 'Jaldi Hire');

        foreach ($userEmail as $email) {
            $mail->addAddress($email);
        }
        $mail->addReplyTo('info@allyted.com', 'Information');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = recruiterBody($body);
        $mail->send();
    } catch (Exception $e) {
    }
}

function jobPstEmpMail($userEmail, $subject, $body)
{

    try {
        // $mail = initializePhpMailer();
        global $mail;

        $mail->setFrom('info@allyted.com', 'Jaldi Hire');

        // foreach ($userEmail as $email) {
        $mail->addAddress($userEmail);
        // }
        $mail->addReplyTo('info@allyted.com', 'Information');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = jobPstMail($body);
        $mail->send();
    } catch (Exception $e) {
    }
}

function jobPstwithoutagent($userEmail, $subject, $body)
{

    try {
        // $mail = initializePhpMailer();
        global $mail;

        $mail->setFrom('info@allyted.com', 'Jaldi Hire');

        // foreach ($userEmail as $email) {
        $mail->addAddress($userEmail);
        // }
        $mail->addReplyTo('info@allyted.com', 'Information');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = agentwithoutjob($body);
        $mail->send();
    } catch (Exception $e) {
    }
}
function jobPstwithagent($userEmail, $subject, $body)
{

    try {
        // $mail = initializePhpMailer();
        global $mail;

        $mail->setFrom('info@allyted.com', 'Jaldi Hire');

        // foreach ($userEmail as $email) {
        $mail->addAddress($userEmail);
        // }
        $mail->addReplyTo('info@allyted.com', 'Information');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = agentwithjob($body);
        $mail->send();
    } catch (Exception $e) {
    }
}
