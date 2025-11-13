<?php
session_start();
include __DIR__ . '/../includes/db.php'; // database connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - SK CONNECT</title>
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="../ALL_CSS/dashboard.css">
</head>
<body class="dashboard-body">

<header>
  <div class="header-left">
    <img src="../images/sklogo.png" alt="SK Logo">
    <div>
      <div class="logo-text">
        <span class="sk">SK</span> <span class="connect">CONNECT</span>
      </div>
      <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
    </div>
  </div>
  <a href="logout.php" class="logout-btn">Logout</a>
</header>

<nav>
  <ul>
    <li><a href="../ALL_PHP/dashboard.php" class="active">Home</a></li>
    <li><a href="../ALL_HTML/about.html">About Us</a></li>
    <li><a href="../ALL_PHP/members.php">SK Members</a></li>
    <li><a href="../ALL_PHP/events.php">Events</a></li>
    <li><a href="../ALL_PHP/budget.php">Budget Reports</a></li>
    <li><a href="../ALL_HTML/contact.html">Contact Us</a></li>
    <li><a href="../ALL_PHP/news.php">News & Announcements</a></li>
  </ul>
</nav>

<!-- Main content -->
<main class="dashboard-main">
  <div class="center-image">
    <img src="../images/skfrontpage.png" alt="SK Front Page">
  </div>

  <!-- Welcome text below image -->
  <div class="welcome-text">
    <h2>Welcome to SK CONNECT</h2>
    <p>Your gateway to community projects, events, and more!</p>
  </div>
</main>

<footer>
  © <?= date("Y") ?> SK CONNECT. All rights reserved.
</footer>

</body>
</html>
