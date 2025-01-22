<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $contact = htmlspecialchars(trim($_POST['contact']));

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, contact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $contact);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
