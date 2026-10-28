<?php

include '../../includes/db-connection/db.php';
include "../vendor/autoload.php";
require '../vendor/phpmailer/PHPMailerAutoload.php';

set_time_limit(10000);

$query = 'SELECT allyted_member_id, recruiter_auto_create_id, validity, notification_sent FROM tbl_allyted_team WHERE status=1 LIMIT 20';
$queryVal = mysqli_query($con, $query);

while ($each = mysqli_fetch_assoc($queryVal)) {
    $allytedId = $each['allyted_member_id'];
    $allytedAuto = $each['recruiter_auto_create_id'];
    $validity = $each['validity'];
    $notificationSent = $each['notification_sent'];

    $validityDate = new DateTime($validity);
    $currentDate = new DateTime();

    $validityDateMinusOne = clone $validityDate;
    $validityDateMinusOne->modify('-1 day');

    if ($currentDate->format('Y-m-d') === $validityDateMinusOne->format('Y-m-d') && $notificationSent == 0) {
        $insertnoti = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM tbl_allyted_team WHERE recruiter_auto_create_id="' . $allytedAuto . '" AND status=1'));

        if ($insertnoti) {
            $roles = $insertnoti['role'];
            $email = $insertnoti['member_email'];
            $adminId = $insertnoti['created_by'];


            if ($roles == 'Agent') {
                $text = "Your validity will expire on <$validity>!";
                $Id = $allytedAuto;
                $admintext = "<$allytedAuto> validity will expire on <$validity>!";
                $InsertNotification = mysqli_query($con, "INSERT INTO tbl_notification SET notify_to='Agent',notification_text='" . $text . "',activity='Masters',notification_type='info',agent_id='" . $Id . "'");

                $InsertadminNotification = mysqli_query($con, "INSERT INTO tbl_notification SET notify_to='Admin',notification_text='" . $admintext . "',activity='Masters',notification_type='info',admin_id='" . $adminId . "'");

                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = 'smtpout.secureserver.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'jaldihireteam@mmworkspace.com';
                $mail->Password = 'Jaldihire@321$#';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');
                $mail->addAddress($email);
                $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');

                $mail->isHTML(true);
                $mail->Subject = 'Validity Expiry Notice';
                $mail->Body    = "Dear Agent, <br><br> Please note that your validity will expire on <b>" . $validity . "</b>.";

                if ($mail->send()) {
                    mysqli_query($con, "UPDATE tbl_allyted_team SET notification_sent = 1 WHERE recruiter_auto_create_id='" . $allytedAuto . "'");
                }
            } elseif ($roles == 'Recruiter') {
                $text = "Your validity will expire on <$validity>!";
                $Id = $allytedAuto;
                $admintext = "<$allytedAuto> validity will expire on <$validity>!";
                $InsertNotification = mysqli_query($con, "INSERT INTO tbl_notification SET notify_to='Recruiter',notification_text='" . $text . "',activity='Masters',notification_type='info',recruiter_id='" . $Id . "'");

                $InsertadminNotification = mysqli_query($con, "INSERT INTO tbl_notification SET notify_to='Admin',notification_text='" . $admintext . "',activity='Masters',notification_type='info',admin_id='" . $adminId . "'");
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = 'smtpout.secureserver.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'jaldihireteam@mmworkspace.com';
                $mail->Password = 'Jaldihire@321$#';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');
                $mail->addAddress($email);
                $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');

                $mail->isHTML(true);
                $mail->Subject = 'Validity Expiry Notice';
                $mail->Body    = "Dear Recruiter, <br><br> Please note that your validity will expire on <b>" . $validity . "</b>.";

                if ($mail->send()) {
                    mysqli_query($con, "UPDATE tbl_allyted_team SET notification_sent = 1 WHERE recruiter_auto_create_id='" . $allytedAuto . "'");
                }
            }
        }
    } elseif ($currentDate->format('Y-m-d') >= $validityDate->format('Y-m-d') && $notificationSent == 1) {
        $updateQuery = "UPDATE tbl_allyted_team SET status = 0 WHERE recruiter_auto_create_id='" . $allytedAuto . "'";
        if (mysqli_query($con, $updateQuery)) {
            echo "Status updated to 0 for recruiter_auto_create_id: $allytedAuto";
        }
    }
}
