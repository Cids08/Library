<?php
session_start();

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $isbn = $_POST['isbn'];
    $subject = $category;
    $available = $_POST['available'];

    $stmt = $pdo->prepare("SELECT cover_image FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    $coverImage = $book['cover_image'];

    if (!empty($_FILES['cover']['name'])) {
        // Delete old image if not default
        if ($book['cover_image'] != "../public/images/default.jpg") {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $book['cover_image']);
        }

        $targetDir = "../public/images/books/";
        $fileName = basename($_FILES["cover"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["cover"]["tmp_name"], $targetFilePath)) {
                $coverImage = "../public/images/books/" . $fileName;
            } else {
                echo json_encode(['success' => false, 'error' => 'Image upload failed']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid image format']);
            exit;
        }
    }

    $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, category = ?, isbn = ?, subject = ?, cover_image = ?, available = ? WHERE id = ?");
    $stmt->execute([$title, $author, $category, $isbn, $subject, $coverImage, $available, $id]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}