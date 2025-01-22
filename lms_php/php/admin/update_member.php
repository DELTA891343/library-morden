<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = intval($_POST['member_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $membership_type = htmlspecialchars(trim($_POST['membership_type']));

    $stmt = $conn->prepare("UPDATE members SET name = ?, contact = ?, membership_type = ? WHERE member_id = ?");
    $stmt->bind_param("sssi", $name, $contact, $membership_type, $member_id);

    if ($stmt->execute()) {
        echo "Member updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
