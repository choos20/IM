<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// ✅ If you don’t have created_at column, remove it from the query
$result = $conn->query("SELECT id, fullname, username, email, created_at FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add User - SK CONNECT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container mt-5">
  <h2>Manage Users</h2>
  <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Full Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Date Created</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['fullname']) ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="get_users.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="fullname" class="form-control mb-3" placeholder="Full Name" required>
          <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
          <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

          <!-- ✅ Fixed PHP tag for compatibility -->
          <input type="password" name="password" class="form-control mb-3" placeholder="Auto-generated Password" readonly value="<?php echo substr(md5(time()), 0, 8); ?>">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
