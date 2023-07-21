<?php
session_start();
include_once "../is-authenticated.php";
require_once "../config/database.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM positions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Position Deleted!";
        header('Location: ./');
    } else {
        $_SESSION['error_msg'] = "Position Not Deleted!";
    }

    $stmt->close();
}

$conn->close();
