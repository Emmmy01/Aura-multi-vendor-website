<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM requests WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<script>alert('Request cancelled successfully.'); window.location.href='view-requests.php';</script>";
    } else {
        echo "Failed to cancel request.";
    }
}
?>
