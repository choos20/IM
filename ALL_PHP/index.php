<?php
session_start();
include __DIR__ . '/../includes/db.php'; // Make sure this path is correct

// Redirect logged-in users
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            header("Location: dashboard.php");
            exit;
        }
    }

    // Generic error message
    $error = "Invalid username/email or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SK CONNECT - Login</title>
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

  main.container {
    background: rgba(255,255,255,0.95);
    padding: 40px 50px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
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
    font-size: 30px;
    font-weight: bold;
    margin-bottom: 30px;
  }

  .logo-text .sk { color: #e63b2e; }
  .logo-text .connect { color: #0056a6; }

  label {
    display: block;
    margin: 18px 0 8px;
    font-weight: 600;
    font-size: 1rem;
    color: #333;
  }

  input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1.5px solid #ddd;
    font-size: 1rem;
    box-sizing: border-box;
    transition: all 0.3s ease;
  }

  input:focus {
    outline: none;
    border-color: #e63b2e;
    box-shadow: 0 0 8px rgba(230, 59, 46, 0.4);
  }

  button {
    width: 100%;
    background: linear-gradient(45deg, #e63b2e, #0056a6);
    color: white;
    font-weight: 700;
    padding: 14px 0;
    margin-top: 28px;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  button:hover {
    background: linear-gradient(45deg, #0056a6, #e63b2e);
    transform: scale(1.02);
  }

  .error {
    background-color: #f8d7da;
    color: #842029;
    border: 1.5px solid #f5c2c7;
    padding: 15px 20px;
    border-radius: 10px;
    margin: 20px 0 0;
    font-weight: 600;
  }

  .links {
    margin-top: 30px;
    font-size: 0.95rem;
    color: #555;
  }

  .links a {
    color: #e63b2e;
    font-weight: 600;
    text-decoration: none;
  }

  .links a:hover {
    color: #0056a6;
    text-decoration: underline;
  }
</style>
</head>
<body>

<main class="container">
  <div class="logo">
    <img src="../images/sklogo.png" alt="SK Logo">
  </div>

  <div class="logo-text">
    <span class="sk">SK</span> <span class="connect">CONNECT</span>
  </div>

  <form action="" method="post" novalidate>
    <label for="user">Username or Email</label>
    <input type="text" id="user" name="user" required value="<?= htmlspecialchars($_POST['user'] ?? '') ?>">

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Log in</button>

    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
  </form>

  <div class="links">
    <p>Forgot password? <a href="forgot.php">Reset here</a></p>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
  </div>
</main>

</body>
</html>
