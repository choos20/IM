<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: events.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $date = $_POST['event_date'];
  $desc = $_POST['description'];

  $stmt = $conn->prepare("UPDATE events SET title = ?, event_date = ?, description = ? WHERE id = ?");
  $stmt->bind_param("sssi", $title, $date, $desc, $id);
  $stmt->execute();

  header("Location: events.php");
  exit();
}

$result = $conn->query("SELECT * FROM events WHERE id = $id");
$event = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Event</title>
  <link rel="stylesheet" href="../ALL_CSS/events.css">
  <style>
    /* ===========================
       FORM STYLING
    ============================ */
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e63b2e 0%, #ffd166 50%, #0056a6 100%);
      color: #fff;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    main.form-section {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(6px);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      width: 90%;
      max-width: 600px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #fff;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.4);
    }

    form input[type="text"],
    form input[type="date"],
    form textarea {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: none;
      font-size: 1rem;
    }

    form textarea {
      height: 120px;
      resize: vertical;
    }

    form input:focus,
    form textarea:focus {
      outline: 2px solid #ffd166;
    }

    .btn-primary {
      width: 100%;
      background: linear-gradient(45deg, #e63b2e, #0056a6);
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      font-size: 1.1rem;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      background: linear-gradient(45deg, #0056a6, #e63b2e);
      transform: scale(1.05);
    }

    /* ===========================
       FIXED BACK BUTTON
    ============================ */
    .back-container {
      position: fixed;
      bottom: 70px; /* above footer */
      left: 25px;
      z-index: 100;
    }

    .back-btn {
      text-decoration: none;
      color: #fff;
      background: linear-gradient(45deg, #e63b2e, #0056a6);
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .back-btn:hover {
      background: linear-gradient(45deg, #0056a6, #e63b2e);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.4);
    }

    footer {
      margin-top: auto;
      text-align: center;
      color: #fff;
      padding: 15px 0;
      font-size: 0.9rem;
      background: rgba(0, 0, 0, 0.3);
      width: 100%;
      position: fixed;
      bottom: 0;
    }
  </style>
</head>
<body>
  <main class="form-section">
    <h2>Edit Event</h2>
    <form method="POST">
      <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
      <input type="date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required>
      <textarea name="description" required><?= htmlspecialchars($event['description']) ?></textarea>
      <button type="submit" class="btn-primary">Update Event</button>
    </form>
  </main>

  <!-- Back Button -->
  <div class="back-container">
    <a href="events.php" class="back-btn">← Back</a>
  </div>

  <footer>
    © <?= date("Y") ?> SK CONNECT. All rights reserved.
  </footer>
</body>
</html>
