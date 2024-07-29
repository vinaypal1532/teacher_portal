<?php

include '../teacher_portal/db.php';

if (isset($_GET['id']))
{
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

?>
