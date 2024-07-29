<?php

include '../teacher_portal/db.php';
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['id']) && isset($data['name']) && isset($data['subject']) && isset($data['marks']))
{
    $id = $data['id'];
    $name = $data['name'];
    $subject = $data['subject'];
    $marks = $data['marks'];

    $stmt = $conn->prepare("UPDATE students SET name = ?, subject = ?, marks = ? WHERE id = ?");

    if ($stmt->execute([$name, $subject, $marks, $id]))
    {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

?>
