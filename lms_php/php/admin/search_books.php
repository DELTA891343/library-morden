<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search_query = htmlspecialchars(trim($_GET['query']));

    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("ss", $search_term, $search_term);

    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    echo json_encode($books);

    $stmt->close();
    $conn->close();
}
?>
