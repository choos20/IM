<?php
session_start();
require __DIR__ . '/../includes/db.php';

if(!isset($_SESSION['reset_user_id']) || !isset($_SESSION['otp_verified'])){
    header("Location: forgot.php");
    exit;
}

$message = '';
$user_id = $_SESSION['reset_user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $user_id);
        $stmt->execute();

        // Clear session
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['otp_verified']);

        $message = "Password updated successfully. <a href='login.php'>Login here</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
<h2>Reset Password</h2>
<?php if($message) echo "<p style='color:red;'>$message</p>"; ?>
<form method="post">
    <label>New Password:</label>
    <input type="password" name="password" required>
    <label>Confirm Password:</label>
    <input type="password" name="confirm" required>
    <button type="submit">Reset Password</button>
</form>
</body>
</html>
