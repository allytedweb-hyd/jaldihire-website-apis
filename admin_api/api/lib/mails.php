<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin");

require '../vendor/phpmailer/PHPMailerAutoload.php';
require '../vendor/autoload.php';
include 'mail_templates.php';




// Initialize PHPMailer
function initializePhpMailer() {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jaldihireteam@mmworkspace.com';
        $mail->Password = 'Jaldihire@321$#';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->isHTML(true);
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
    return $mail;
}

// 📌 Function to generate ICS file
// function generateGoogleICS($eventDetails) {
//     $icsContent = "BEGIN:VCALENDAR\r\n";
//     $icsContent .= "VERSION:2.0\r\n";
//     $icsContent .= "PRODID:-//Google Inc//Google Calendar 70.9054//EN\r\n";
//     $icsContent .= "METHOD:REQUEST\r\n";
//     $icsContent .= "BEGIN:VEVENT\r\n";
//     $icsContent .= "UID:" . uniqid() . "\r\n";
//     $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
//     $icsContent .= "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['start'])) . "\r\n";
//     $icsContent .= "DTEND:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['end'])) . "\r\n";
//     $icsContent .= "SUMMARY:Interview Invitation\r\n";
//     $icsContent .= "DESCRIPTION:Join the interview via Jaldihire: " . $eventDetails['meet_link'] . "\r\n";
//     $icsContent .= "LOCATION:Jaldihire\r\n";  
//     $icsContent .= "ATTENDEE;CN=Applicant;RSVP=TRUE:mailto:" . $eventDetails['applicant_email'] . "\r\n";
//     $icsContent .= "ATTENDEE;CN=Interviewer;RSVP=TRUE:mailto:" . $eventDetails['interviewer_email'] . "\r\n";
//     $icsContent .= "ORGANIZER;CN=Jaldhi Hire:mailto:jaldihireteam@mmworkspace.com\r\n";
//     $icsContent .= "CLASS:PUBLIC\r\n";
//     $icsContent .= "SEQUENCE:0\r\n";
//     $icsContent .= "STATUS:CONFIRMED\r\n";
//     $icsContent .= "TRANSP:OPAQUE\r\n";
//     $icsContent .= "END:VEVENT\r\n";
//     $icsContent .= "END:VCALENDAR\r\n";

//     $icsFile = __DIR__ . "/google_invite.ics";
//     file_put_contents($icsFile, $icsContent);
//     return $icsFile;
// }
function generateGoogleICS($eventDetails, $recipientType) {
    $meetLink = $recipientType === 'applicant' ? $eventDetails['applicant_meet_link'] : $eventDetails['interviewer_meet_link'];

    $icsContent = "BEGIN:VCALENDAR\r\n";
    $icsContent .= "VERSION:2.0\r\n";
    $icsContent .= "PRODID:-//Google Inc//Google Calendar 70.9054//EN\r\n";
    $icsContent .= "METHOD:REQUEST\r\n";
    $icsContent .= "BEGIN:VEVENT\r\n";
    $icsContent .= "UID:" . uniqid() . "\r\n";
    $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
    $icsContent .= "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['start'])) . "\r\n";
    $icsContent .= "DTEND:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['end'])) . "\r\n";
    $icsContent .= "SUMMARY:Interview Invitation\r\n";
    $icsContent .= "DESCRIPTION:Join the interview via Jaldihire: " . $meetLink . "\r\n";
    $icsContent .= "LOCATION:Jaldihire\r\n";  
    $icsContent .= "ATTENDEE;CN=$recipientType;RSVP=TRUE:mailto:" . $eventDetails[$recipientType . '_email'] . "\r\n";
    $icsContent .= "ORGANIZER;CN=Jaldhi Hire:mailto:jaldihireteam@mmworkspace.com\r\n";
    $icsContent .= "CLASS:PUBLIC\r\n";
    $icsContent .= "SEQUENCE:0\r\n";
    $icsContent .= "STATUS:CONFIRMED\r\n";
    $icsContent .= "TRANSP:OPAQUE\r\n";
    $icsContent .= "END:VEVENT\r\n";
    $icsContent .= "END:VCALENDAR\r\n";

    $icsFile = __DIR__ . "/google_invite_$recipientType.ics";
    file_put_contents($icsFile, $icsContent);
    return $icsFile;
}


// function generateOutlookICS($eventDetails) {
//     $icsContent = "BEGIN:VCALENDAR\r\n";
//     $icsContent .= "VERSION:2.0\r\n";
//     $icsContent .= "PRODID:-//Microsoft Corporation//Outlook 16.0 MIMEDIR//EN\r\n";
//     $icsContent .= "METHOD:REQUEST\r\n";
//     $icsContent .= "BEGIN:VEVENT\r\n";
//     $icsContent .= "UID:" . uniqid() . "\r\n";
//     $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
//     $icsContent .= "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['start'])) . "\r\n";
//     $icsContent .= "DTEND:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['end'])) . "\r\n";
//     $icsContent .= "SUMMARY:Interview Invitation\r\n";
//     $icsContent .= "DESCRIPTION:Join the interview via Jaldihire: " . $eventDetails['meet_link'] . "\r\n";
//     $icsContent .= "LOCATION:Jaldihire\r\n";
//     $icsContent .= "ATTENDEE;CN=Applicant;RSVP=TRUE:mailto:" . $eventDetails['applicant_email'] . "\r\n";
//     $icsContent .= "ATTENDEE;CN=Interviewer;RSVP=TRUE:mailto:" . $eventDetails['interviewer_email'] . "\r\n";
//     $icsContent .= "ORGANIZER;CN=Jaldhi Hire:mailto:info@jaldihire.com\r\n";
//     $icsContent .= "CLASS:PUBLIC\r\n";
//     $icsContent .= "SEQUENCE:0\r\n";
//     $icsContent .= "STATUS:CONFIRMED\r\n";
//     $icsContent .= "TRANSP:OPAQUE\r\n";
//     $icsContent .= "END:VEVENT\r\n";
//     $icsContent .= "END:VCALENDAR\r\n";

//     $icsFile = __DIR__ . "/outlook_invite.ics";
//     file_put_contents($icsFile, $icsContent);
//     return $icsFile;
// }
function generateOutlookICS($eventDetails, $recipientType) {
    $meetLink = $recipientType === 'applicant' ? $eventDetails['applicant_meet_link'] : $eventDetails['interviewer_meet_link'];

    $icsContent = "BEGIN:VCALENDAR\r\n";
    $icsContent .= "VERSION:2.0\r\n";
    $icsContent .= "PRODID:-//Microsoft Corporation//Outlook 16.0 MIMEDIR//EN\r\n";
    $icsContent .= "METHOD:REQUEST\r\n";
    $icsContent .= "BEGIN:VEVENT\r\n";
    $icsContent .= "UID:" . uniqid() . "\r\n";
    $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
    $icsContent .= "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['start'])) . "\r\n";
    $icsContent .= "DTEND:" . gmdate('Ymd\THis\Z', strtotime($eventDetails['end'])) . "\r\n";
    $icsContent .= "SUMMARY:Interview Invitation\r\n";
    $icsContent .= "DESCRIPTION:Join the interview via Jaldihire: " . $meetLink . "\r\n";
    $icsContent .= "LOCATION:Jaldihire\r\n";  
    $icsContent .= "ATTENDEE;CN=$recipientType;RSVP=TRUE:mailto:" . $eventDetails[$recipientType . '_email'] . "\r\n";
    $icsContent .= "ORGANIZER;CN=Jaldhi Hire:mailto:info@jaldihire.com\r\n";
    $icsContent .= "CLASS:PUBLIC\r\n";
    $icsContent .= "SEQUENCE:0\r\n";
    $icsContent .= "STATUS:CONFIRMED\r\n";
    $icsContent .= "TRANSP:OPAQUE\r\n";
    $icsContent .= "END:VEVENT\r\n";
    $icsContent .= "END:VCALENDAR\r\n";

    $icsFile = __DIR__ . "/outlook_invite_$recipientType.ics";
    file_put_contents($icsFile, $icsContent);
    return $icsFile;
}


function sendEventEmail($recipientEmail, $recipientType, $eventDetails) {
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');
        $mail->addAddress($recipientEmail);
        $mail->Subject = 'Interview Invitation - JALDHI HIRE';

        if ($recipientType === 'applicant') {

            $mail->Body = "
                <html>
                <head><title>Interview Invitation</title></head>
                <body>
                    <h2>Dear Applicant,</h2>
                    <p>We are pleased to invite you for an interview.</p>
                    <p><strong>Date & Time:</strong> {$eventDetails['date']} at {$eventDetails['starttime']}-{$eventDetails['endtime']}</p>
                    <p><strong>Meeting Link:</strong> <a href='{$eventDetails['applicant_meet_link']}'>Join Meeting</a></p>
                    <p>Best Regards,<br>HR Team</p>
                </body>
                </html>
            ";
        } else {
            $mail->Body = "
                <html>
                <head><title>Interview Scheduled</title></head>
                <body>
                    <h2>Dear Interviewer,</h2>
                    <p>An interview has been scheduled.</p>
                    <p><strong>Date & Time:</strong> {$eventDetails['date']} at {$eventDetails['starttime']}-{$eventDetails['endtime']}</p>
                    <p><strong>Applicant:</strong> {$eventDetails['applicant_email']}</p>
                    <p><strong>Meeting Link:</strong> <a href='{$eventDetails['interviewer_meet_link']}'>Join Meeting</a></p>
                    <p>Best Regards,<br>HR Team</p>
                </body>
                </html>
            ";
        }

        // Determine ICS type based on email domain
        // $domain = substr(strrchr($recipientEmail, "@"), 1);
        // if (strpos($domain, 'gmail.com') !== false) {
        //     $icsFilePath = generateGoogleICS($eventDetails);
        // } elseif (strpos($domain, 'outlook.com') !== false || strpos($domain, 'hotmail.com') !== false) {
        //     $icsFilePath = generateOutlookICS($eventDetails);
        // } else {
        //     $icsFilePath = generateGoogleICS($eventDetails); // Default to Google ICS
        // }
        $domain = substr(strrchr($recipientEmail, "@"), 1);
if (strpos($domain, 'gmail.com') !== false) {
    $icsFilePath = generateGoogleICS($eventDetails, $recipientType);
} elseif (strpos($domain, 'outlook.com') !== false || strpos($domain, 'hotmail.com') !== false) {
    $icsFilePath = generateOutlookICS($eventDetails, $recipientType);
} else {
    $icsFilePath = generateGoogleICS($eventDetails, $recipientType); // Default to Google ICS
}


        // Attach the correct ICS file
        $mail->addAttachment($icsFilePath, 'interview_invite.ics');

        if ($mail->send()) {
            return ["message" => "$recipientType email sent successfully", "status" => true];
        } else {
            return ["message" => "Failed to send $recipientType email", "error" => $mail->ErrorInfo, "status" => false];
        }
    } catch (Exception $e) {
        return ["message" => "Error sending email to $recipientType: " . $mail->ErrorInfo, "status" => false];
    }
}



function sendEmail($userEmail, $subject, $heading, $body, $assignedJobId = '')
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = recruiterAssignmail($heading, $body, $assignedJobId);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function add_allyted($userEmail, $subject, $heading, $body, $auto_create_id)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = allyted_teampass($heading, $body, $auto_create_id);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function CreateAgent($userEmail, $subject, $heading, $body, $auto_create_id)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = agentCreatepassword($heading, $body, $auto_create_id);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


function applicantMail($userEmail, $subject, $heading, $body, $assignedJobId = '')
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = recruiterAssignmail($heading, $body, $assignedJobId);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function AgentEmail($userEmail, $subject, $greeting, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = AgentAssignmail($greeting, $body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


function employerEmail($userEmail, $subject, $greeting, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = AgentAssignToEmployermail($greeting, $body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function jobAssignAgentEmailtoAgent($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = jobAssingAgentToAgent($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function registerApplicantmail($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = infomails($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function registerApplicantSupport($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = supmails($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function jobAssignAgentEmailtoEmployer($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = jobAssingAgentToEmp($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


function jobAssignToRecruiter($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = jobAssingRecruiterMail($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function recruiterAssignApplicantToApplicant($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = assignRecruitermail($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function recruiterAssignApplicantToRecruiter($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = assignRecruitermail($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function requestMailAccess($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = requestMail($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function applicant_Email($userEmail, $subject, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        // if ($attachment !== null) {
        //     $mail->addAttachment($attachment);
        // }
        // 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = applicantmailwhensubmitTOEmployer($body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}




// new mail templatesss///////////////////////////////////////////////////////////////////////////////////////////////////////

function acceptInterviewmailtoEmployer($userEmail, $subject, $greeting, $body)
{
    try {
        $mail = initializePhpMailer();
        $mail->setFrom('jaldihireteam@mmworkspace.com', 'Jaldi Hire');
        $mail->addAddress($userEmail);
        $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'Information');


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = AgentAssignToEmployermail($greeting, $body);
        $mail->send();
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}