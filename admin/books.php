<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['student_number']) || 
   ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin')) {
    header("Location: /Library/frontend/auth.php");
    exit();
}

try {
    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all books from DB
    $stmt_books = $pdo->query("SELECT * FROM books ORDER BY title ASC");
    $books = $stmt_books->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <link rel="stylesheet" href="../public/css/admindashboard.css">
    <style>
        /* Inline CSS for Books Management Page */
        .actions i {
            cursor: pointer;
            margin-right: 10px;
            padding: 5px;
            font-size: 16px;
            transition: transform 0.2s ease;
        }
        .actions i:hover {
            transform: scale(1.2);
        }
        .actions i.fa-edit {
            color: #f59e0b;
        }
        .actions i.fa-trash {
            color: #ef4444;
        }
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }
        .modal-content {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            animation: fadeIn 0.3s forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .modal-header h3 {
            font-size: 18px;
            color: #1f2937;
        }
        .modal-header button {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s ease;
        }
        .modal-header button:hover {
            color: #ef4444;
        }
        .modal-body input,
        .modal-body select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .modal-body input:focus,
        .modal-body select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .modal-footer {
            text-align: right;
            margin-top: 10px;
        }
        .modal-footer button {
            margin-left: 10px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        #saveBookBtn {
            background-color: #3b82f6;
            color: white;
            border: none;
        }
        #saveBookBtn:hover {
            background-color: #2563eb;
        }
        #cancelModal {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }
        #cancelModal:hover {
            background-color: #e5e7eb;
        }
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .search-bar input {
            padding: 10px 15px;
            width: 300px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        #addBookBtn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }
        #addBookBtn:hover {
            background-color: #2563eb;
        }
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .table-scroll {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead th {
            background-color: #f9fafb;
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tbody tr:hover {
            background-color: #f9fafb;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-overdue {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <?php include '../includes/adminsidebar.php'; ?>
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <h2 class="page-title">Books Management</h2>
        </header>
        <!-- Page Content -->
        <main class="dashboard">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search books...">
                <button id="addBookBtn" class="view-all"><i class="fas fa-plus"></i> Add Book</button>
            </div>
            <div class="table-container">
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>ISBN</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="booksTable">
                            <!-- Books will be loaded dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Book Modal -->
<div class="modal" id="bookModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Book</h3>
            <button id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="bookId">
            <label for="bookCover">Cover Image</label>
            <input type="file" id="bookCover" accept="image/*">
            <input type="text" id="bookTitle" placeholder="Book Title" required>
            <input type="text" id="bookAuthor" placeholder="Author" required>
            <input type="text" id="bookCategory" placeholder="Category" required>
            <input type="text" id="bookISBN" placeholder="ISBN" required>
            <select id="bookStatus">
                <option value="1">Available</option>
                <option value="0">Borrowed</option>
            </select>
        </div>
        <div class="modal-footer">
            <button id="saveBookBtn">Save</button>
            <button id="cancelModal">Cancel</button>
        </div>
    </div>
</div>

<script>
    const booksTable = document.getElementById("booksTable");
    const modal = document.getElementById("bookModal");
    const saveBtn = document.getElementById("saveBookBtn");
    const addBookBtn = document.getElementById("addBookBtn");
    const closeModalBtn = document.getElementById("closeModal");
    const cancelModalBtn = document.getElementById("cancelModal");

    let editingBookId = null;

    function clearModal() {
        document.getElementById("bookId").value = "";
        document.getElementById("bookTitle").value = "";
        document.getElementById("bookAuthor").value = "";
        document.getElementById("bookCategory").value = "";
        document.getElementById("bookISBN").value = "";
        document.getElementById("bookStatus").value = "1";
        document.getElementById("bookCover").value = "";
        document.getElementById("modalTitle").innerText = "Add Book";
    }

    function loadBooks() {
        fetch("fetch_books.php")
            .then(res => res.json())
            .then(data => {
                booksTable.innerHTML = "";
                data.forEach(book => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td><img src="${book.cover_image}" alt="Cover" width="50"></td>
                        <td>${book.title}</td>
                        <td>${book.author}</td>
                        <td>${book.category}</td>
                        <td>${book.isbn}</td>
                        <td><span class="status-badge ${book.available == 1 ? 'status-active' : 'status-overdue'}">${book.available == 1 ? 'Available' : 'Borrowed'}</span></td>
                        <td class="actions">
                            <i class="fas fa-edit" onclick="editBook(${book.id})"></i>
                            <i class="fas fa-trash" onclick="deleteBook(${book.id})"></i>
                        </td>
                    `;
                    booksTable.appendChild(row);
                });
            });
    }

    function editBook(id) {
        fetch(`fetch_books.php?id=${id}`)
            .then(res => res.json())
            .then(book => {
                document.getElementById("bookId").value = book.id;
                document.getElementById("bookTitle").value = book.title;
                document.getElementById("bookAuthor").value = book.author;
                document.getElementById("bookCategory").value = book.category;
                document.getElementById("bookISBN").value = book.isbn;
                document.getElementById("bookStatus").value = book.available;
                document.getElementById("modalTitle").innerText = "Edit Book";
                modal.style.display = "flex";
                editingBookId = id;
            });
    }

    function deleteBook(id) {
        if (confirm("Are you sure you want to delete this book?")) {
            fetch("delete_book.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id })
            })
            .then(res => res.json())
            .then(() => loadBooks());
        }
    }

    saveBtn.addEventListener('click', () => {
        const newBook = {
            title: document.getElementById('bookTitle').value.trim(),
            author: document.getElementById('bookAuthor').value.trim(),
            category: document.getElementById('bookCategory').value.trim(),
            isbn: document.getElementById('bookISBN').value.trim(),
            status: document.getElementById('bookStatus').value
        };

        if (!newBook.title || !newBook.author || !newBook.category || !newBook.isbn) {
            alert("Please fill in all fields.");
            return;
        }

        fetch('add_book.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newBook)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Book saved successfully!");
                modal.style.display = 'none';
                clearModal();
                loadBooks(); // Refresh book list
            } else {
                alert("Error saving book: " + data.message);
            }
        })
        .catch(error => {
            console.error("AJAX Error:", error);
            alert("An error occurred while saving the book.");
        });
    });

    addBookBtn.addEventListener("click", () => {
        clearModal();
        document.getElementById("modalTitle").innerText = "Add Book";
        modal.style.display = "flex";
    });

    closeModalBtn.addEventListener("click", () => modal.style.display = "none");
    cancelModalBtn.addEventListener("click", () => modal.style.display = "none");

    document.addEventListener("DOMContentLoaded", () => {
        loadBooks();
    });
</script>
</body>
</html>