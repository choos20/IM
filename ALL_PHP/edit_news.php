<?php
include __DIR__ . '/../includes/db.php';

// Fetch announcement by ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM news WHERE id = $id");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Announcement not found.'); window.location.href='news.php';</script>";
        exit;
    }
} else {
    header("Location: news.php");
    exit;
}

// Update announcement
if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Announcement updated successfully!'); window.location.href='news.php';</script>";
        } else {
            echo "<script>alert('Failed to update announcement.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Announcement - SK CONNECT</title>
  <style>
    /* ===========================
       GLOBAL STYLES
    =========================== */
    body {
      font-family: 'Poppins', system-ui, Arial, sans-serif;
      margin: 0;
      scroll-behavior: smooth;
      background: linear-gradient(135deg, #e63b2e 0%, #ffd166 50%, #0056a6 100%);
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===========================
       HEADER
    =========================== */
    header {
      background-color: rgba(0, 0, 0, 0.4);
      display: flex;
      align-items: center;
      padding: 10px 20px;
      color: #fff;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .header-left img {
      height: 60px;
    }

    .logo-text {
      font-size: 1.5rem;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .logo-text .sk {
      color: #ffd166;
    }

    .logo-text .connect {
      color: #fff;
    }

    /* ===========================
       NAVIGATION
    =========================== */
    nav {
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    nav ul {
      display: flex;
      flex-wrap: wrap;
      list-style: none;
      margin: 0;
      padding: 0;
      justify-content: center;
    }

    nav li { margin: 0; }

    nav a {
      display: block;
      padding: 16px 22px;
      text-decoration: none;
      color: #333;
      font-weight: 700;
      font-size: 1.3rem;
      transition: 0.3s;
    }

    nav a:hover, nav a.active {
      background: #e63b2e;
      color: #fff;
    }

    /* ===========================
       MAIN CONTENT
    =========================== */
    main.news-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    main h1 {
      font-size: 2.2rem;
      margin-bottom: 20px;
      text-align: center;
    }

    form {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      padding: 30px 40px;
      border-radius: 12px;
      max-width: 600px;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 12px 15px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      resize: vertical;
      outline: none;
    }

    textarea {
      min-height: 150px;
    }

    button {
      background: #ffd166;
      color: #000;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #e63b2e;
      color: #fff;
    }

    .back-btn {
      text-decoration: none;
      color: #fff;
      background: rgba(255,255,255,0.2);
      padding: 10px 15px;
      border-radius: 6px;
      text-align: center;
      transition: 0.3s;
      display: inline-block;
    }

    .back-btn:hover {
      background: #ffd166;
      color: #000;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../images/sklogo.png" alt="SK CONNECT Logo">
      <div class="logo-text"><span class="sk">SK</span> <span class="connect">CONNECT</span></div>
    </div>
  </header>

  <main class="news-main">
    <h1>Edit Announcement</h1>
    <form method="POST">
      <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
      <textarea name="content" required><?= htmlspecialchars($row['content']) ?></textarea>
      <button type="submit" name="update">Save Changes</button>
      <a href="news.php" class="back-btn">← Back</a>
    </form>
  </main>
</body>
</html>
