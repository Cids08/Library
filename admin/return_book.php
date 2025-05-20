<?php
session_start();

if (!isset($_SESSION['student_number'])) {
    header("Location: /Library/frontend/auth.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_POST['history_id'])) {
        die("History ID not provided.");
    }

    $history_id = $_POST['history_id'];
    $return_date = date('Y-m-d');

    // Get book_id before updating
    $stmt = $pdo->prepare("SELECT book_id FROM borrow_history WHERE id = ?");
    $stmt->execute([$history_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $book_id = $row['book_id'];

    // Update return date
    $stmt = $pdo->prepare("UPDATE borrow_history SET return_date = ? WHERE id = ?");
    $stmt->execute([$return_date, $history_id]);

    // Mark book as available
    $stmt = $pdo->prepare("UPDATE books SET available = 1 WHERE id = ?");
    $stmt->execute([$book_id]);

    // Redirect back to dashboard
    header("Location: ../student.php");
    exit();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>