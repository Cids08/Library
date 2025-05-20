<?php
session_start();
require 'db.php'; // Make sure you have a valid db.php file

$search = $_GET['q'] ?? '';

// Query to search books
$sql = "SELECT * FROM books WHERE 
        title LIKE :query OR 
        author LIKE :query OR 
        subject LIKE :query 
        ORDER BY title ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['query' => "%$search%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($results);
exit;
?>