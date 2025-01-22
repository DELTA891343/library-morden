<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = intval($_POST['book_id']);
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $genre = htmlspecialchars(trim($_POST['genre']));
    $isbn = htmlspecialchars(trim($_POST['isbn']));
    $published_year = intval($_POST['published_year']);
    $available_copies = intval($_POST['available_copies']);

    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, genre = ?, isbn = ?, published_year = ?, available_copies = ? WHERE book_id = ?");
    $stmt->bind_param("sssssii", $title, $author, $genre, $isbn, $published_year, $available_copies, $book_id);

    if ($stmt->execute()) {
        echo "Book updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
