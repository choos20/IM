<?php
include __DIR__ . '/../includes/db.php'; // adjust path if needed

$message = "";

// Handle form submission (CREATE)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_member'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $position = trim($_POST['position']);

    // Handle file upload
    $photo = "";
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $photo = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    $stmt = $conn->prepare("INSERT INTO members (first_name, last_name, position, photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $position, $photo);
    if ($stmt->execute()) {
        $message = "✅ Member added successfully!";
    } else {
        $message = "❌ Error adding member.";
    }
}

// Handle DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM members WHERE id = $id");
    header("Location: members.php");
    exit;
}

// Fetch members
$result = $conn->query("SELECT * FROM members ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SK Members - SK CONNECT</title>
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
        <li><a href="../ALL_PHP/budget.php">Budget Reports</a></li>
        <li><a href="../ALL_HTML/gallery.html">Gallery / Media</a></li>
        <li><a href="../ALL_HTML/contact.html">Contact Us</a></li>
        <li><a href="../ALL_PHP/news.php">News & Announcements</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h2>SK MEMBERS MANAGEMENT</h2>

    <?php if (!empty($message)): ?>
      <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="first_name" placeholder="First Name" required>
      <input type="text" name="last_name" placeholder="Last Name" required>
      <input type="text" name="position" placeholder="Position" required>
      <input type="file" name="photo" accept="image/*">
      <button type="submit" name="add_member">Add Member</button>
    </form>

    <table>
      <tr>
        <th>Photo</th>
        <th>Full Name</th>
        <th>Position</th>
        <th>Actions</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><img src="<?= htmlspecialchars($row['photo']) ?>" class="member-photo" alt=""></td>
        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
        <td><?= htmlspecialchars($row['position']) ?></td>
        <td class="actions">
          <a href="edit_member.php?id=<?= $row['id'] ?>">Edit</a> |
          <a href="members.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this member?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </main>

  <footer>
    © <?= date("Y") ?> SK CONNECT. All rights reserved.
  </footer>
</body>
</html>
