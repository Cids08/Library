<?php
session_start();

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$title = trim($data['title']);
$author = trim($data['author']);
$category = trim($data['category']);
$isbn = trim($data['isbn']);
$status = $data['status'] ?? '1';

if (!$title || !$author || !$category || !$isbn) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO books (title, author, category, isbn, subject, available, cover_image)
                           VALUES (?, ?, ?, ?, ?, ?, '../public/images/default.jpg')");
    $stmt->execute([$title, $author, $category, $isbn, $category, $status]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}