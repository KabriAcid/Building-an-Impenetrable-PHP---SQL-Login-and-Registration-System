<?php
// I imported required libraries
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Phpml\AnomalyDetection\GaussianMixture;

// I implemented user registration
function register_user($username, $email, $password) {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hashed_password]);
}

// I implemented user authentication
function authenticate_user($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        send_otp($username, $user['email']);
        $_SESSION['username'] = $username;
        return true;
    }
    return false;
}

// I implemented OTP sending
function send_otp($username, $email) {
    global $pdo;
    $otp_code = rand(100000, 999999);
    $otp_expiration = date("Y-m-d H:i:s", strtotime("+10 minutes"));
    $stmt = $pdo->prepare("UPDATE users SET otp_code = ?, otp_expiration = ? WHERE username = ?");
    $stmt->execute([$otp_code, $otp_expiration, $username]);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_email_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@example.com', 'Secure Login System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: $otp_code";

        $mail->send();
    } catch (Exception $e) {
        error_log("OTP email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

// I implemented OTP verification
function verify_otp($username, $otp_code) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT otp_code, otp_expiration FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['otp_code'] === $otp_code && new DateTime() < new DateTime($user['otp_expiration'])) {
        $stmt = $pdo->prepare("UPDATE users SET otp_code = NULL, otp_expiration = NULL WHERE username = ?");
        $stmt->execute([$username]);
        return true;
    }
    return false;
}

// I implemented failed attempts recording
function record_failed_attempt($username) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = failed_attempts + 1 WHERE username = ?");
    $stmt->execute([$username]);

    $stmt = $pdo->prepare("SELECT failed_attempts FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['failed_attempts'] >= 2) {
        $lockout_time = date("Y-m-d H:i:s", strtotime("+24 hours"));
        $stmt = $pdo->prepare("UPDATE users SET lockout_time = ?, is_active = 0 WHERE username = ?");
        $stmt->execute([$lockout_time, $username]);
    }
}

// I implemented account lock check
function is_account_locked($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT lockout_time FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && new DateTime() < new DateTime($user['lockout_time'])) {
        return true;
    }
    return false;
}

// I implemented admin notification
function notify_admin($admin_email, $username) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_email_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@example.com', 'Secure Login System');
        $mail->addAddress($admin_email);

        $mail->isHTML(true);
        $mail->Subject = 'Account Lockout Notification';
        $mail->Body = "The account with username $username has been locked due to multiple failed login attempts.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Admin notification email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

// I implemented role retrieval
function get_user_role($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user['role'];
}

// I implemented admin email retrieval
function get_admin_email() {
    // Assuming admin email is stored in a config file or database
    return 'admin@example.com';
}

// I validated user input
function validate_user($username, $password) {
    return !empty($username) && !empty($password) && strlen($username) <= 50 && strlen($password) <= 255;
}
?>
