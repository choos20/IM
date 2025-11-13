<?php
session_start();
include __DIR__ . '/../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM news ORDER BY date_posted DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News & Announcements - SK CONNECT</title>
  <link rel="stylesheet" href="../ALL_CSS/news.css">
</head>
<body>

<header>
  <div class="header-left">
    <img src="../images/sklogo.png" alt="SK CONNECT Logo">
    <div class="logo-text">
      <span class="sk">SK</span> <span class="connect">CONNECT</span>
    </div>
  </div>
</header>

<!-- Navigation -->
<nav>
  <ul>
    <li><a href="../ALL_PHP/dashboard.php" class="active">Home</a></li>
    <li><a href="../ALL_HTML/about.html">About Us</a></li>
    <li><a href="../ALL_PHP/members.php">SK Members</a></li>
    <li><a href="../ALL_PHP/events.php">Events</a></li>
    <li><a href="../ALL_PHP/budget.php">Budget Reports</a></li>
    <li><a href="../ALL_HTML/contact.html">Contact Us</a></li>
    <li><a href="../ALL_PHP/news.php" class="active">News & Announcements</a></li>
  </ul>
</nav>

<main class="news-main">
  <section class="news-hero">
    <h1>News & Announcements</h1>
    <p>Stay updated with the latest community news from SK CONNECT.</p>
  </section>

  <section class="news-section">
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <article class="news-card">
          <h2><?= htmlspecialchars($row['title']) ?></h2>
          <p class="date">Posted on <?= date("F j, Y", strtotime($row['date_posted'])) ?></p>
          <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>

          <div class="actions">
            <a href="edit_news.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
            <a href="delete_news.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Delete this announcement?')">Delete</a>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">No announcements yet.</p>
    <?php endif; ?>
  </section>
<?php if (isset($_GET['status'])): ?>
  <div class="notification <?= $_GET['status'] ?>">
    <?= $_GET['status'] === 'success' 
        ? '✅ Announcement added successfully!' 
        : '❌ Failed to add announcement. Please try again.' ?>
  </div>
<?php endif; ?>

  <section class="add-news">
    <h2>Add New Announcement</h2>
    <form action="add_news.php" method="POST">
      <input type="text" name="title" placeholder="Title" required>
      <textarea name="content" placeholder="Write your announcement..." required></textarea>
      <button type="submit" name="add">Add Announcement</button>
    </form>
  </section>

  <div class="back-container">
    <a href="dashboard.php" class="back-btn">← Back</a>
  </div>
</main>
