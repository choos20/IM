<?php
include 'db.php'; // uses mysqli connection in this project

// Handle Add Budget Entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_budget'])) {
    $type = $_POST['type'];
    $category = $_POST['category'] ?? '';
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'] ?? '';
    $receipt = $_FILES['receipt'] ?? null;

    // Upload receipt if exists
    $receiptName = null;
    if ($receipt && $receipt['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/budget_receipts/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $receiptName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($receipt['name']));
        move_uploaded_file($receipt['tmp_name'], $uploadDir . $receiptName);
    }

  // Insert into DB using mysqli (prepared statement)
  $insertSql = "INSERT INTO budget (type, category, amount, date, description, receipt_path) VALUES (?, ?, ?, ?, ?, ?)";
  if ($stmt = $conn->prepare($insertSql)) {
    $amountParam = (float)$amount;
    // bind params: s = string, d = double
    $stmt->bind_param("ssdsss", $type, $category, $amountParam, $date, $description, $receiptName);
    $stmt->execute();
    $stmt->close();
  } else {
    error_log("Budget insert prepare failed: " . $conn->error);
  }

    header("Location: budget.php");
    exit;
}

// Fetch all budget entries
$entries = [];
$result = $conn->query("SELECT * FROM budget ORDER BY date DESC");
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $entries[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget - SK CONNECT</title>
    <link rel="stylesheet" href="../ALL_CSS/budget.css">
</head>
<body>

<header>
  <div class="logo-container">
    <img src="../images/sklogo.png" alt="SK Logo" class="logo">
    <h1 class="logo-text">
      <span class="sk">SK</span> <span class="connect">CONNECT</span>
    </h1>
  </div>

  <nav>
    <ul>
      <li><a href="dashboard.php">Home</a></li>
      <li><a href="../ALL_HTML/about.html">About Us</a></li>
      <li><a href="members.php">SK Members</a></li>
      <li><a href="../ALL_HTML/events.html">Events</a></li>
      <li><a href="#" class="active">Budget Reports</a></li>
      <li><a href="../ALL_HTML/contact.html">Contact</a></li>
      <li><a href="../ALL_HTML/news.html">News & Announcements</a></li>
    </ul>
  </nav>
</header>

<main class="budget-main">

  <section class="hero-section">
      <h2>Manage Budget</h2>
      <p>Add new income or expenses, view past entries, and upload receipts.</p>
  </section>

  <section class="form-section">
      <h3>Add Budget Entry</h3>
      <form action="" method="POST" enctype="multipart/form-data" class="budget-form">
          <label>Type</label>
          <select name="type" required>
              <option value="Income">Income</option>
              <option value="Expense">Expense</option>
          </select>

          <label>Category</label>
          <input type="text" name="category" placeholder="Optional category">

          <label>Amount</label>
          <input type="number" name="amount" step="0.01" required>

          <label>Date</label>
          <input type="date" name="date" required>

          <label>Description</label>
          <textarea name="description" placeholder="Optional notes"></textarea>

          <label>Receipt (Optional)</label>
          <input type="file" name="receipt" accept=".jpg,.png,.pdf">

          <button type="submit" name="add_budget">Add Entry</button>
      </form>
  </section>

  <section class="entries-section">
      <h3>All Budget Entries</h3>
      <?php foreach($entries as $entry): ?>
      <div class="entry-card">
          <p><strong>Type:</strong> <?= htmlspecialchars($entry['type']) ?></p>
          <p><strong>Category:</strong> <?= htmlspecialchars($entry['category']) ?></p>
          <p><strong>Amount:</strong> ₱<?= number_format($entry['amount'],2) ?></p>
          <p><strong>Date:</strong> <?= date("F j, Y", strtotime($entry['date'])) ?></p>
          <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($entry['description'])) ?></p>
          <?php if ($entry['receipt_path']): ?>
              <p><strong>Receipt:</strong> <a href="../uploads/budget_receipts/<?= htmlspecialchars($entry['receipt_path']) ?>" target="_blank">View</a></p>
          <?php endif; ?>
      </div>
      <?php endforeach; ?>
  </section>

</main>

<footer>
  © <?= date("Y") ?> SK CONNECT. All rights reserved.
</footer>

</body>
</html>
