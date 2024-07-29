<?php

include '../teacher_portal/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE name = ?");
    $stmt->execute([$name]);
    $existingStudent = $stmt->fetch();

    if ($existingStudent) {
        echo json_encode(['success' => false, 'message' => 'Student with the same name already exists']);
    } else {     
        $stmt = $conn->prepare("INSERT INTO students (name, subject, marks) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $subject, $marks])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add student']);
        }
    }
    exit;
}
?>
