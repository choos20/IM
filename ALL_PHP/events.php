<?php
include 'db.php';
$result = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events - SK CONNECT</title>
  <link rel="stylesheet" href="../ALL_CSS/events.css">
</head>
<body>

<header>
  <div class="logo-container">
    <img src="../images/sklogo.png" alt="SK Logo" class="logo">
    <h1 class="logo-text"><span class="sk">SK</span> <span class="connect">CONNECT</span></h1>
  </div>

  <nav>
    <ul>
      <li><a href="dashboard.php">Home</a></li>
      <li><a href="../ALL_HTML/about.html">About Us</a></li>
      <li><a href="../ALL_PHP/members.php">SK Members</a></li>
      <li><a href="../ALL_PHP/events.php" class="active">Events</a></li>
      <li><a href="../ALL_PHP/budget.php">Budget Reports</a></li>
      <li><a href="../ALL_HTML/contact.html">Contact</a></li>
      <li><a href="../ALL_PHP/news.php">News</a></li>
    </ul>
  </nav>
</header>

<main class="events-main">

  <section class="hero-section">
    <div class="hero-content">
      <h2>Manage Events</h2>
      <p>Add, update, or delete SK CONNECT events.</p>
    </div>
  </section>

  <section class="form-section">
    <h3>Add New Event</h3>
    <form action="add_event.php" method="POST" enctype="multipart/form-data" class="event-form">
      <input type="text" name="title" placeholder="Event Title" required>
      <input type="date" name="event_date" required>
      <textarea name="description" placeholder="Event Description" required></textarea>
      <input type="file" name="image" accept="image/*" required>
      <button type="submit" class="btn-primary">Add Event</button>
    </form>
  </section>

  <section class="events-section">
    <h3>All Events</h3>

    <?php while($row = $result->fetch_assoc()): ?>
      <div class="event-card">
        <?php if ($row['image_path']): ?>
          <img src="../uploads/event_images/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Event Image">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p class="date"><?php echo date("F j, Y", strtotime($row['event_date'])); ?></p>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <div class="btn-container">
          <a href="update_event.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
          <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
        </div>
      </div>
    <?php endwhile; ?>
  </section>

</main>

<footer>
  © <?= date("Y") ?> SK CONNECT. All rights reserved.
</footer>

</body>
</html>
