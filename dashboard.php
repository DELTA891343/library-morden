<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();

$user = $_SESSION['user'];
$borrowed_books = [];

// Get user's borrowed books
$stmt = $conn->prepare("
    SELECT b.title, b.author, br.borrow_date, br.due_date, br.status
    FROM borrowings br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = ?
    ORDER BY br.borrow_date DESC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $borrowed_books[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Library Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .books-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .books-table th,
        .books-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .books-table th {
            background: var(--primary-color);
            color: white;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-borrowed { background: #fef3c7; color: #92400e; }
        .status-returned { background: #d1fae5; color: #065f46; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" class="logo-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <span>LMS</span>
            </div>
            <div class="nav-links">
                <a href="catalog.php">Book Catalog</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="dashboard">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p>Manage your borrowed books and reservations</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Currently Borrowed</h3>
                <p class="stat-number"><?php echo count(array_filter($borrowed_books, function($book) { return $book['status'] === 'borrowed'; })); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Books Borrowed</h3>
                <p class="stat-number"><?php echo count($borrowed_books); ?></p>
            </div>
        </div>

        <h2>Your Borrowed Books</h2>
        <?php if (empty($borrowed_books)): ?>
            <p>You haven't borrowed any books yet.</p>
        <?php else: ?>
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowed_books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($book['borrow_date'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($book['due_date'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $book['status']; ?>">
                                    <?php echo ucfirst($book['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>