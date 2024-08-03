<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once(plugin_dir_path(__FILE__) . '../vendor/autoload.php');

// Generate and send OTP
function generate_and_send_otp($user_email) {
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    send_otp_email($user_email, $otp);
    // Store OTP in user meta or session for later verification
}

// Send OTP email
function send_otp_email($user_email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Update with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // Update with your SMTP username
        $mail->Password = 'your-email-password'; // Update with your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('no-reply@example.com', 'Secure Login');
        $mail->addAddress($user_email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = 'Your OTP code is ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        log_security_error('OTP email could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }
}

// Verify OTP during login
function verify_otp_on_login($user_login, $user) {
    // Verify OTP for the user
    // Redirect or handle OTP verification
}
