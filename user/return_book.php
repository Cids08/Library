<?php
session_start();

if (!isset($_SESSION['student_number'])) {
    header("Location: auth.php");
    exit();
}

if (!isset($_POST['history_id'])) {
    header("Location: student.php");
    exit();
}

$history_id = $_POST['history_id'];
$student_number = $_SESSION['student_number'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure this record belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM borrow_history WHERE id = ? AND student_number = ?");
    $stmt->execute([$history_id, $student_number]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record || $record['return_date']) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'This book has already been returned.'];
        header("Location: student.php");
        exit();
    }

    // Update return date
    $stmt = $pdo->prepare("UPDATE borrow_history SET return_date = CURDATE() WHERE id = ?");
    $stmt->execute([$history_id]);

    $_SESSION['message'] = ['type' => 'success', 'text' => 'Book returned successfully.'];
    header("Location: student.php");
    exit();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}