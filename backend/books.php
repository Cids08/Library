<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <div class="topbar-left">
                    <button class="menu-button" id="mobile-menu-button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="page-title">Books Management</h2>
                </div>
                <div class="topbar-right">
                    <div class="notification">
                        <button>
                            <i class="fas fa-bell"></i>
                        </button>
                        <span class="notification-badge"></span>
                    </div>
                    <div class="mobile-profile">
                        <img class="profile-image" src="/api/placeholder/40/40" alt="Admin profile picture">
                    </div>
                </div>
            </header>
                        <!-- Mobile Sidebar -->
            <div id="mobile-sidebar" class="sidebar-overlay" style="display: none;">
                <div class="mobile-sidebar">
                    <div class="mobile-sidebar-header">
                        <h1>Library Admin</h1>
                        <button class="close-button" id="close-sidebar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="sidebar-content">
                        <nav class="sidebar-nav">
                            <div class="sidebar-section">
                                <p class="sidebar-section-title">Main</p>
                                <a href="AdminDashboard.php" class="sidebar-link">
                                    <i class="fas fa-home"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="books.php" class="sidebar-link">
                                    <i class="fas fa-book"></i>
                                    <span>Books</span>
                                </a>
                                <a href="members.php" class="sidebar-link">
                                    <i class="fas fa-users"></i>
                                    <span>Members</span>
                                </a>
                            </div>
                            <div class="sidebar-section">
                                <p class="sidebar-section-title">Transactions</p>
                                <a href="borrowed.php" class="sidebar-link">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span>Borrowed</span>
                                </a>
                                <a href="overdue.php" class="sidebar-link">
                                    <i class="fas fa-clock"></i>
                                    <span>Overdue</span>
                                </a>
                                <a href="history.php" class="sidebar-link">
                                    <i class="fas fa-history"></i>
                                    <span>History</span>
                                </a>
                            </div>
                            <div class="sidebar-section">
                                <p class="sidebar-section-title">System</p>
                                <a href="reports.php" class="sidebar-link">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Reports</span>
                                </a>
                                <a href="settings.php" class="sidebar-link">
                                    <i class="fas fa-cog"></i>
                                    <span>Settings</span>
                                </a>
                            </div>
                        </nav>

                    </div>
                    <div class="sidebar-footer">
                        <div class="profile">
                            <img class="profile-image" src="/api/placeholder/40/40" alt="Admin profile picture">
                            <div class="profile-info">
                                <p class="profile-name">John Doe</p>
                                <p class="profile-role">Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>ISBN</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="booksTable">
                                <!-- Rows added dynamically -->
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
                <input type="text" id="bookTitle" placeholder="Book Title">
                <input type="text" id="bookAuthor" placeholder="Author">
                <input type="text" id="bookCategory" placeholder="Category">
                <input type="text" id="bookISBN" placeholder="ISBN">
                <select id="bookStatus">
                    <option value="available">Available</option>
                    <option value="borrowed">Borrowed</option>
                </select>
            </div>
            <div class="modal-footer">
                <button id="saveBookBtn">Save</button>
                <button id="cancelModal">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const books = [
            { title: 'The Great Gatsby', author: 'F. Scott Fitzgerald', category: 'Fiction', isbn: '9780743273565', status: 'available' },
            { title: '1984', author: 'George Orwell', category: 'Dystopian', isbn: '9780451524935', status: 'borrowed' },
        ];

        const tableBody = document.getElementById('booksTable');
        const modal = document.getElementById('bookModal');
        const addBookBtn = document.getElementById('addBookBtn');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');
        const saveBookBtn = document.getElementById('saveBookBtn');
        const searchInput = document.getElementById('searchInput');

        function renderTable(data) {
            tableBody.innerHTML = '';
            data.forEach((book, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${book.title}</td>
                    <td>${book.author}</td>
                    <td>${book.category}</td>
                    <td>${book.isbn}</td>
                    <td><span class="status-badge ${book.status === 'available' ? 'status-active' : 'status-overdue'}">${book.status}</span></td>
                    <td class="actions">
                        <i class="fas fa-edit text-yellow-600" onclick="editBook(${index})"></i>
                        <i class="fas fa-trash text-red-600" onclick="deleteBook(${index})"></i>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        function clearModal() {
            document.getElementById('bookTitle').value = '';
            document.getElementById('bookAuthor').value = '';
            document.getElementById('bookCategory').value = '';
            document.getElementById('bookISBN').value = '';
            document.getElementById('bookStatus').value = 'available';
        }

        let editIndex = null;

        function editBook(index) {
            const book = books[index];
            document.getElementById('modalTitle').innerText = 'Edit Book';
            document.getElementById('bookTitle').value = book.title;
            document.getElementById('bookAuthor').value = book.author;
            document.getElementById('bookCategory').value = book.category;
            document.getElementById('bookISBN').value = book.isbn;
            document.getElementById('bookStatus').value = book.status;
            editIndex = index;
            modal.style.display = 'flex';
        }

        function deleteBook(index) {
            if (confirm('Are you sure you want to delete this book?')) {
                books.splice(index, 1);
                renderTable(books);
            }
        }

        saveBookBtn.addEventListener('click', () => {
            const newBook = {
                title: document.getElementById('bookTitle').value,
                author: document.getElementById('bookAuthor').value,
                category: document.getElementById('bookCategory').value,
                isbn: document.getElementById('bookISBN').value,
                status: document.getElementById('bookStatus').value
            };

            if (editIndex !== null) {
                books[editIndex] = newBook;
                editIndex = null;
            } else {
                books.push(newBook);
            }

            renderTable(books);
            modal.style.display = 'none';
            clearModal();
        });

        addBookBtn.addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = 'Add Book';
            clearModal();
            modal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const filtered = books.filter(book =>
                book.title.toLowerCase().includes(query) ||
                book.author.toLowerCase().includes(query) ||
                book.category.toLowerCase().includes(query)
            );
            renderTable(filtered);
        });

        document.addEventListener('DOMContentLoaded', () => {
            renderTable(books);
        });
    </script>
</body>
</html>
