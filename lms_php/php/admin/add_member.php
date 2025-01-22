<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $membership_type = htmlspecialchars(trim($_POST['membership_type']));

    $stmt = $conn->prepare("INSERT INTO members (name, contact, membership_type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $contact, $membership_type);

    if ($stmt->execute()) {
        echo "Member added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
