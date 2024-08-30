<?php
include 'connect.php';
require 'vendor/autoload.php'; // Only needed if using Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'pablojaninekarla@gmail.com'; // Your SMTP username
        $mail->Password = 'hypl zqwy cihu fjxw'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('pablojaninekarla@gmail.com', 'Mindsoothe');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body="<b>Dear User,</b>
            <p>We received a request to reset your password.</p>
            <p>Your OTP code is <b> $otp </b></p>
            <p>Please do not share it with anyone.</p>
            <br><br>
            <p>With regrads,</p>
            <b>Mindsoothe</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

if (isset($_POST['resetPassword'])) {
    $email = $_POST['email'];

    // Validate email syntax
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    // Check if email exists
    $checkEmail = "SELECT * FROM usersacc WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
         // Store OTP and email in the session
         $_SESSION['otp'] = $otp;
         $_SESSION['email'] = $email;
 
         if (sendOTP($email, $otp)) {
            echo "<script type='text/javascript'>
                    alert('OTP has been sent to your email.');
                    window.location.href = 'enter_otp.html';
                  </script>";
            exit();
        } else {
            echo "<script type='text/javascript'>
                    alert('Failed to send OTP.');
                  </script>";
        }
    } else {
        echo "<script type='text/javascript'>
                alert('Email address not found!');
              </script>";
    }
 }
 ?>