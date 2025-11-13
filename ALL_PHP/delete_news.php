<?php
include __DIR__ . '/../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Announcement deleted successfully!'); window.location.href='news.php';</script>";
    } else {
        echo "<script>alert('Failed to delete announcement.'); window.location.href='news.php';</script>";
    }
} else {
    header("Location: news.php");
    exit;
}
?>
