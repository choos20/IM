<?php
include __DIR__ . '/../includes/db.php'; // adjust path if needed

// Get member ID from URL
if (!isset($_GET['id'])) {
    header("Location: members.php");
    exit;
}

$id = intval($_GET['id']);
$message = "";

// Fetch existing member data
$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Member not found.");
}

$member = $result->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_member'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $position = trim($_POST['position']);
    $photo = $member['photo']; // keep old photo by default

    // Handle file upload if new photo added
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $photo = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    $stmt = $conn->prepare("UPDATE members SET first_name=?, last_name=?, position=?, photo=? WHERE id=?");
    $stmt->bind_param("ssssi", $first_name, $last_name, $position, $photo, $id);

    if ($stmt->execute()) {
        $message = "✅ Member updated successfully!";
        // Refresh data after update
        $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $member = $stmt->get_result()->fetch_assoc();
    } else {
        $message = "❌ Error updating member.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Member - SK CONNECT</title>
  <link rel="stylesheet" href="../ALL_CSS/members.css">
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="../images/sklogo.png" alt="SK Logo" class="logo">
      <h1 class="logo-text"><span class="sk">SK</span> <span class="connect">CONNECT</span></h1>
    </div>
    <nav>
      <ul>
        <li><a href="../ALL_PHP/dashboard.php">Home</a></li>
        <li><a href="../ALL_HTML/about.html">About Us</a></li>
        <li><a href="../ALL_PHP/members.php" class="active">SK Members</a></li>
        <li><a href="../ALL_PHP/events.php">Events</a></li>
        <li><a href="../ALL_HTML/budget.html">Budget Reports</a></li>
        <li><a href="../ALL_HTML/gallery.html">Gallery / Media</a></li>
        <li><a href="../ALL_HTML/contact.html">Contact Us</a></li>
        <li><a href="../ALL_PHP/news.php">News & Announcements</a></li>
      </ul>
    </nav>
  </header>

  <main class="edit-member">
    <h2>Edit Member</h2>

    <?php if (!empty($message)): ?>
      <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="edit-form">
      <img src="<?= htmlspecialchars($member['photo']) ?>" class="member-photo" alt="Member Photo">

      <input type="text" name="first_name" value="<?= htmlspecialchars($member['first_name']) ?>" required>
      <input type="text" name="last_name" value="<?= htmlspecialchars($member['last_name']) ?>" required>
      <input type="text" name="position" value="<?= htmlspecialchars($member['position']) ?>" required>

      <label class="upload-label">Change Photo (optional)</label>
      <input type="file" name="photo" accept="image/*">

      <button type="submit" name="update_member">Update Member</button>
    </form>

    <a href="members.php" class="back-link">← Back to Members List</a>
  </main>

  <footer>
    © <?= date("Y") ?> SK CONNECT. All rights reserved.
  </footer>
</body>
</html>
