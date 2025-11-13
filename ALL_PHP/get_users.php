<?php
include 'includes/db.php';

$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$fullname || !$email || !$username || !$password) {
    die("All fields are required.");
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

// ✅ Use 'password' (not 'password_hash')
$stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $fullname, $email, $username, $hashed);

if ($stmt->execute()) {
    header("Location: add_user.php?success=1");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
