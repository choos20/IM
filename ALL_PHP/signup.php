<?php
session_start();

// Database connection
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // add your MySQL password if you have one
$DB_NAME = 'napinas_db';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_errno) {
    die("Database connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!$fullname) {
        $error = "Full name is required.";
    } elseif (!$username || strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!$password || strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $stmt->close();

            // Hash password and insert
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $fullname, $username, $email, $password_hash);

            if ($stmt->execute()) {
                $success = "✅ Account created successfully! You can <a href='index.php'>log in here</a>.";
                $fullname = $username = $email = '';
            } else {
                $error = "Error during signup: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>SK CONNECT - Sign Up</title>
  <style>
    body {
    font-family: 'Poppins', system-ui, Arial, sans-serif;
    background: linear-gradient(135deg, #e63b2e, #ffd166, #0056a6);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

    .container {
      background: #fff;
      padding: 40px 50px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 420px;
      text-align: center;
    }
    .logo img {
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }
    .logo-text {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 25px;
    }
    .logo-text .sk { color: #e63b2e; }
    .logo-text .connect { color: #0056a6; }

    label {
      display: block;
      margin: 18px 0 8px;
      font-weight: 600;
      color: #555;
    }

    input[type="text"], input[type="email"], input[type="password"] {
      width: 100%;
      padding: 12px 14px;
      border-radius: 8px;
      border: 1.8px solid #ddd;
      font-size: 1rem;
      box-sizing: border-box;
      transition: 0.3s;
    }

    input:focus {
      outline: none;
      border-color: #e63b2e;
      box-shadow: 0 0 8px rgba(230, 59, 46, 0.4);
    }

    button {
      width: 100%;
      padding: 14px 0;
      margin-top: 28px;
      background: linear-gradient(45deg, #e63b2e, #0056a6);
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: linear-gradient(45deg, #0056a6, #e63b2e);
    }

    .back-button {
      margin-top: 12px;
      background-color: #aaa;
      padding: 12px 0;
      font-weight: 700;
      border-radius: 10px;
      cursor: pointer;
      border: none;
      width: 100%;
    }

    .back-button:hover { background-color: #888; }

    .error {
      background-color: #f8d7da;
      color: #842029;
      border: 1.5px solid #f5c2c7;
      padding: 15px 20px;
      border-radius: 10px;
      margin-top: 20px;
      font-weight: 600;
    }

    .success {
      background-color: #d1e7dd;
      color: #0f5132;
      border: 1.5px solid #badbcc;
      padding: 15px 20px;
      border-radius: 10px;
      margin-top: 20px;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="../images/sklogo.png" alt="SK Logo">
    </div>
    <div class="logo-text">
      <span class="sk">SK</span> <span class="connect">CONNECT</span>
    </div>

    <form method="post" novalidate>
      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" required value="<?= htmlspecialchars($fullname ?? '') ?>" />

      <label for="username">Username</label>
      <input type="text" id="username" name="username" minlength="3" required value="<?= htmlspecialchars($username ?? '') ?>" />

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>" />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" minlength="6" required />

      <button type="submit">Sign up</button>
    </form>

    <button class="back-button" onclick="window.location.href='index.php'">Back to Login</button>

    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
      <p class="success"><?= $success ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
