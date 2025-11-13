<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Asia/Manila'); // Set your timezone

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];

        // Generate 6-digit OTP
        $otp = strval(rand(100000, 999999));
        $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Store OTP
        $stmt = $conn->prepare("INSERT INTO password_otp (user_id, otp, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $otp, $expires);
        $stmt->execute();

        // Send OTP via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'napinasritchiebob@gmail.com'; // your email
            $mail->Password   = 'ihxcyprwymolfuqt';    // app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('napinasritchiebob@gmail.com', 'SK Connect');
            $mail->addAddress($row['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset OTP';
            $mail->Body    = "Your OTP for password reset is: <b>$otp</b>. It expires in 10 minutes.";

            $mail->send();

            $_SESSION['reset_user_id'] = $user_id;
            header("Location: verify_otp.php");
            exit;

        } catch (Exception $e) {
            $message = "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        $message = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
<h2>Forgot Password</h2>
<?php if($message) echo "<p style='color:red;'>$message</p>"; ?>
<form method="post">
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Send OTP</button>
</form>
</body>
</html>
