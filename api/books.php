<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "libmas");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// CORS Headers for local development
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = $_GET['search'] ?? '';
    $stmt = $conn->prepare("SELECT id, title, author, category, isbn, IF(available=1, 'available', 'borrowed') AS status FROM books WHERE available = 1");
    if (!empty($search)) {
        $stmt = $conn->prepare("SELECT id, title, author, category, isbn, IF(available=1, 'available', 'borrowed') AS status FROM books WHERE title LIKE ? OR author LIKE ?");
        $searchTerm = "%$search%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    echo json_encode($books);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO books (title, author, category, isbn, available) VALUES (?, ?, ?, ?, ?)");
    $available = ($data['status'] === 'available') ? 1 : 0;
    $stmt->bind_param("ssssi", $data['title'], $data['author'], $data['category'], $data['isbn'], $available);
    $stmt->execute();
    echo json_encode(['success' => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $available = ($data['status'] === 'available') ? 1 : 0;
    $stmt = $conn->prepare("UPDATE books SET 
        title = ?, 
        author = ?, 
        category = ?, 
        isbn = ?, 
        available = ? 
        WHERE id = ?");
    $stmt->bind_param("ssssii", $data['title'], $data['author'], $data['category'], $data['isbn'], $available, $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(['success' => true]);
}
?>