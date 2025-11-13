<?php
include __DIR__ . '/../includes/db.php';

if (isset($_POST['add'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO news (title, content, date_posted) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            echo "<script>alert('Announcement added successfully!'); window.location.href='news.php';</script>";
        } else {
            echo "<script>alert('Failed to add announcement.'); window.location.href='news.php';</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.'); window.location.href='news.php';</script>";
    }
}
?>
