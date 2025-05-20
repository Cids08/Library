<?php
session_start();

if (!isset($_SESSION['student_number'])) {
    header("Location: ../auth.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get book ID from request
    if (!isset($_GET['book_id'])) {
        die("Book ID not provided.");
    }
    $book_id = $_GET['book_id'];
    $student_number = $_SESSION['student_number'];
    $date = date('Y-m-d');

    // Start transaction
    $pdo->beginTransaction();

    // Insert into borrow_history
    $stmt = $pdo->prepare("INSERT INTO borrow_history (student_number, book_id, title, author, cover_image, date)
                            SELECT ?, id, title, author, cover_image, ?
                            FROM books WHERE id = ?");
    $stmt->execute([$student_number, $date, $book_id]);

    // Mark book as unavailable
    $stmt = $pdo->prepare("UPDATE books SET available = 0 WHERE id = ?");
    $stmt->execute([$book_id]);

    // Commit transaction
    $pdo->commit();

    // Redirect back to catalog
    header("Location: ../catalog.php");
    exit();

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
}
?>