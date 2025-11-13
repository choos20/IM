<?php
session_start();
require __DIR__ . '/../includes/db.php';

if(!isset($_SESSION['reset_user_id'])){
    header("Location: forgot.php");
    exit;
}

$message = '';
$user_id = $_SESSION['reset_user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $otp = strval($otp); // ensure string

    // Get latest unused OTP
    $stmt = $conn->prepare("SELECT * FROM password_otp WHERE user_id = ? AND used = 0 ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['otp'] === $otp && strtotime($row['expires_at']) >= time()) {
            // Mark OTP as used
            $stmt = $conn->prepare("UPDATE password_otp SET used = 1 WHERE id = ?");
            $stmt->bind_param("i", $row['id']);
            $stmt->execute();

            $_SESSION['otp_verified'] = true;
            header("Location: reset_password.php");
            exit;
        } else {
            $message = "Invalid or expired OTP.";
        }
    } else {
        $message = "No OTP found. Please request again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
</head>
<body>
<h2>Enter OTP</h2>
<?php if($message) echo "<p style='color:red;'>$message</p>"; ?>
<form method="post">
    <input type="text" name="otp" placeholder="6-digit OTP" required>
    <button type="submit">Verify OTP</button>
</form>
</body>
</html>
