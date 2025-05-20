
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Borrowed Management - Library Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../public/css/admindashboard.css" />
</head>
<body>
    <div class="container">
       <?php include '../includes/adminsidebar.php'; ?>

        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <h2 class="page-title">Borrowed Books</h2>
            </header>

            <!-- Borrowed Books Table -->
            <main class="dashboard">
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Current Borrowed Books</h3>
                        <button class="view-all">View All</button>
                    </div>
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Borrower</th>
                                    <th>Borrowed Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="borrowed-books">
                                <!-- Rows will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer">
                        <div class="table-info">
                            Showing 1 to 10 of 25 entries
                        </div>
                        <div class="pagination">
                            <button class="pagination-button">Previous</button>
                            <button class="pagination-button active">1</button>
                            <button class="pagination-button">2</button>
                            <button class="pagination-button">3</button>
                            <button class="pagination-button">Next</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sample data for borrowed books
        const borrowedBooks = [
            {
                title: "The Great Gatsby",
                borrower: "Emma Johnson",
                borrowedDate: "2025-05-15",
                dueDate: "2025-05-29",
                status: "active"
            },
            {
                title: "Pride and Prejudice",
                borrower: "David Brown",
                borrowedDate: "2025-05-10",
                dueDate: "2025-05-24",
                status: "overdue"
            },
            {
                title: "1984",
                borrower: "Sarah Williams",
                borrowedDate: "2025-05-12",
                dueDate: "2025-05-26",
                status: "active"
            },
            {
                title: "Harry Potter and the Sorcerer's Stone",
                borrower: "Robert Wilson",
                borrowedDate: "2025-05-08",
                dueDate: "2025-05-22",
                status: "returned"
            }
        ];

        // Function to get status badge HTML
        function getStatusBadge(status) {
            switch(status) {
                case 'active':
                    return '<span class="status-badge status-active">Active</span>';
                case 'overdue':
                    return '<span class="status-badge status-overdue">Overdue</span>';
                case 'returned':
                    return '<span class="status-badge status-returned">Returned</span>';
                default:
                    return '<span class="status-badge">Unknown</span>';
            }
        }

        // Populate the borrowed books table
        function populateBorrowedBooks() {
            const tableBody = document.getElementById('borrowed-books');
            tableBody.innerHTML = '';

            borrowedBooks.forEach(book => {
                const row = document.createElement('tr');

                // Format dates
                const borrowedDate = new Date(book.borrowedDate).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                const dueDate = new Date(book.dueDate).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                row.innerHTML = `
                    <td>${book.title}</td>
                    <td>${book.borrower}</td>
                    <td>${borrowedDate}</td>
                    <td>${dueDate}</td>
                    <td>${getStatusBadge(book.status)}</td>
                    <td>
                        <div class="flex space-x-2 action-buttons">
                            <button class="action-button view text-indigo-600 hover:text-indigo-900" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-button edit text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-button delete text-red-600 hover:text-red-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        }

        // Mobile sidebar toggle
        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            document.getElementById('mobile-sidebar').style.display = 'block';
        });

        document.getElementById('close-sidebar').addEventListener('click', () => {
            document.getElementById('mobile-sidebar').style.display = 'none';
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            populateBorrowedBooks();
        });
    </script>
</body>
</html>
