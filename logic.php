<?php
include '../teacher_portal/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM teachers WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['loggedin'] = true;
        header('Location: ../teacher_portal/home.php');
    } else {
        echo "Invalid credentials";
    }
}
?>
