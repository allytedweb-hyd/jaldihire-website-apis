<?php
include "../includes/db-connection/db.php";
include "./vendor/autoload.php";
include "../libs/alerts.php";

use Firebase\JWT\JWT;

@$recruiterId = $_GET['recruiterId'];

$q = mysqli_query($con, "select * from tbl_allyted_team where recruiter_auto_create_id='" . $recruiterId . "'");
$f = mysqli_fetch_array($q);

@$name = $f['member_name'];
@$email = $f['member_email'];

if (isset($_POST['submit'])) {

    $pass = $_POST['pass'];
    $cnf = $_POST['cnfpass'];
    if (!$f['password'] == '') {
        // showAlert("error", "Error", " Password already created");
        echo '<script>alert("Password already created")</script>';

        exit();
    }

    $regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_+=])[a-zA-Z\d!@#$%^&*()-_+=]{8,}$/';


    if (preg_match($regex, $pass)) {
        if ($pass != $cnf) {
            echo '<script>alert("password is wrong")</script>';
        } else {
            $hash = password_hash("$cnf", PASSWORD_DEFAULT);

            $update = mysqli_query($con, "update tbl_allyted_team set password='" . $hash . "',status=1 where recruiter_auto_create_id='" . $recruiterId . "'");
            if ($update == true) {
                echo '<script>alert("password is created")</script>';
                $userId = $recruiterId;
                $key = "J@ldiHire@321";
                $issuedAt = new DateTimeImmutable();
                $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
                $payload = array(
                    "user_id" => $userId,
                    'iat' => $issuedAt->getTimestamp(),
                    // Issued at: time when the token was generated
                    'nbf' => $issuedAt->getTimestamp(),
                    // Not before
                    'exp' => $expire,
                    // Expire Time
                );
                $jwt = JWT::encode($payload, $key, 'HS256');
                header("Location: https://admin.jaldihire.com/?id=$jwt");
            } else {
                echo '<script>alert("password is not created")</script>';
            }
        }
    } else {
        echo '<script>alert("Password must contain at least one digit, one lowercase letter, one uppercase letter, one special character, and be at least 8 characters long.")</script>';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/styles1.css" />
    <link rel="icon" href="images/j-logo.png" type="image/x-icon" />
</head>

<body>
    <section class="login">
        <div>
            <img src="images/jal.png" class="jal" alt="login-img">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="logo-ig">
                        <img src="images/j-logo.png" class="j-logo" alt="">
                        <img src="images/jal-logo.png" class="jal-cls" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="account">
                        <h4>Create Account</h4>
                        <p>Login into your account</p>
                    </div>
                    <div class="form-flex">
                        <form method="post">
                            <div class="col-md-12  col-xs-12">
                                <div class="form-grp">
                                    <input type="text" name="" id="" class="form-control" placeholder="User Name"
                                        value="<?php echo $name; ?>">
                                </div>
                            </div>
                            <div class="col-md-12  col-xs-12">
                                <div class="form-grp">
                                    <input type="text" name="" id="" class="form-control" placeholder="Email Address"
                                        value="<?php echo $email; ?>">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="form-grp">
                                    <input type="password" name="pass" id="password-input" class="form-control"
                                        placeholder="Enter Your New Password" required maxlength="10"
                                        onpaste="return false" ondrop="return false">
                                    <span toggle="#password-input" class="eye-icon"></span>
                                </div>
                            </div>
                            <div class="col-md-12  col-xs-12">
                                <div class="form-grp password-content">
                                    <input type="password" name="cnfpass" id="password-input" class="form-control"
                                        placeholder="Confirm Your New Password" required maxlength="10"
                                        onpaste="return false" ondrop="return false">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="jal-btn">
                                    <button type="submit" name="submit">Sign Up</button>
                                </div>
                            </div>
                            <p class="stepp">Once Step a head to join our family </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
            integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const eyeIcon = document.querySelector('.eye-icon');
                const passwordInput = document.querySelector('#password-input');
                eyeIcon.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('hide-password');
                });
            });
        </script>
    </footer>
</body>

</html>