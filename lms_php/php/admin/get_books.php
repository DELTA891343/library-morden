<?php
require '../db.php';

$stmt = $conn->prepare("SELECT * FROM books");
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);

$stmt->close();
$conn->close();
?>
