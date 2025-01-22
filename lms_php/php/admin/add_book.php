<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $genre = htmlspecialchars(trim($_POST['genre']));
    $isbn = htmlspecialchars(trim($_POST['isbn']));
    $published_year = intval($_POST['published_year']);
    $available_copies = intval($_POST['available_copies']);

    $stmt = $conn->prepare("INSERT INTO books (title, author, genre, isbn, published_year, available_copies) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $title, $author, $genre, $isbn, $published_year, $available_copies);

    if ($stmt->execute()) {
        echo "Book added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
