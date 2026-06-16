<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Temporarily set to 1 to see errors
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

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

try {
    // Check if PHPMailer exists
    $phpmailer_path = '../vendor/phpmailer/PHPMailerAutoload.php';
    if (!file_exists($phpmailer_path)) {
        throw new Exception("PHPMailer file not found at: " . $phpmailer_path);
    }
    require $phpmailer_path;

    // Check database connection file
    $db_path = "../../includes/db-connection/db.php";
    if (!file_exists($db_path)) {
        throw new Exception("Database connection file not found at: " . $db_path);
    }
    include $db_path;

    // Check database connection
    if (!isset($con) || !$con) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    $response = ['status' => false, 'message' => 'Invalid request'];

    $json = file_get_contents('php://input');
    if (!$json) {
        throw new Exception("No input data received");
    }

    $data = json_decode($json);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }

    if ($data) {
        $name = $data->name ?? '';
        $mobile = $data->mobile ?? '';
        $email = $data->email ?? '';
        $role = 'Admin';

        // Validate email
        if (empty($email)) {
            echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => false, "message" => "Invalid email format"]);
            exit();
        }

        // Check if email exists
        $email_escaped = mysqli_real_escape_string($con, $email);
        $checkEmail = mysqli_query($con, "SELECT * FROM tbl_login WHERE login_mail = '$email_escaped'");
        
        if (!$checkEmail) {
            throw new Exception("Database query error: " . mysqli_error($con));
        }
        
        if (mysqli_num_rows($checkEmail) > 0) {
            echo json_encode(['status' => false, "message" => "Email Already Exists"]);
            exit();
        }

        // Insert admin
        $name_escaped = mysqli_real_escape_string($con, $name);
        $mobile_escaped = mysqli_real_escape_string($con, $mobile);
        
        $insert_admin = mysqli_query($con, "INSERT INTO tbl_login SET 
            admin_name='$name_escaped',
            user_role='$role',
            mobile='$mobile_escaped',
            login_mail='$email_escaped',
            login_status=0");

        if (!$insert_admin) {
            throw new Exception("Failed to insert admin: " . mysqli_error($con));
        }

        $last_id = mysqli_insert_id($con);

        // Insert notification settings
        $insert_notification = mysqli_query($con, "INSERT INTO allowed_notifications SET 
            user_id='$last_id',
            employer_alerts=1,
            advisor_alerts=1,
            recruiter_alerts=1,
            admin_alerts=1,
            interview_alerts=1,
            job_alerts=1,
            status=1");

        if (!$insert_notification) {
            // Log but don't fail
            error_log("Failed to insert notifications: " . mysqli_error($con));
        }

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->SMTPDebug = 0; // Set to 2 for debugging
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@allytedhrms.cloud';
            $mail->Password = 'Allyted@2025';
            $mail->SMTPSecure = 'ssl'; // Use string instead of constant
            $mail->Port = 465;
            $mail->Timeout = 30;
            $mail->CharSet = 'UTF-8';
            
            // Recipients
            $mail->setFrom('admin@allytedhrms.cloud', 'JALDI HIRE');
            $mail->addAddress($email, $name);
            $mail->addReplyTo('admin@allytedhrms.cloud', 'JALDI HIRE');
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Please select password for Admin login - Jaldi Hire';
            
            // Email body
            $emailBody = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { background: #BFCBCC; font-family: Arial, sans-serif; }
                    .container { background-color: #aecaf0; padding: 20px; }
                    .main-out { margin: auto; text-align: center; max-width: 70%; padding: 50px; }
                    .mail-box1 { background: #fff; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.35); border-radius: 5px; }
                    button { 
                        width: auto; 
                        padding: 10px 20px; 
                        background-color: #8a26ff; 
                        color: white; 
                        border: none;
                        border-radius: 5px; 
                        cursor: pointer;
                        font-weight: 600;
                        margin: 25px 15px;
                    }
                    button:hover { background-color: #063baa; }
                    img { max-width: 200px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="main-out">
                        <div class="logo">
                            <img src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/logo.png" alt="Jaldi Hire Logo" />
                        </div>
                        <div class="mail-box1">
                            <img src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/message-1.gif" alt="Welcome" />
                            <div style="margin-top: 20px;">
                                Hello ' . htmlspecialchars($name) . ',<br><br>
                                Welcome to the Jaldihire family!<br>
                                Please select a password now to gain full access to your admin dashboard.<br><br>
                                <a href="https://jaldihire.com/admin_api/admin-choose-password/index.php?adminId=' . $last_id . '" target="_blank">
                                    <button>Click here to create password</button>
                                </a>
                                <br><br>
                                Regards,<br>
                                Jaldi Hire Team
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
            
            $mail->Body = $emailBody;
            $mail->AltBody = "Hello " . $name . ",\n\nWelcome to Jaldihire family!\nPlease select a password now to gain full access to your admin dashboard.\n\nLink: https://mmworkspace.com/test_jaldhihireadmin/admin-choose-password/index.php?adminId=" . $last_id . "\n\nRegards,\nJaldi Hire Team";
            
            // Send email
            if (!$mail->send()) {
                throw new Exception("Mailer Error: " . $mail->ErrorInfo);
            }
            
            $response = [
                'status' => true, 
                'message' => 'Admin created and email sent successfully',
                'admin_id' => $last_id
            ];
            
        } catch (Exception $e) {
            // Email failed but admin was created
            error_log("Mailer Error for admin ID $last_id: " . $e->getMessage());
            
            $response = [
                'status' => true, 
                'message' => 'Admin created successfully but email could not be sent',
                'admin_id' => $last_id,
                'email_error' => $e->getMessage()
            ];
        }
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Internal server error',
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    error_log("API Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
}
?>