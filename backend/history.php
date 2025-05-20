<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Borrowing History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../public/css/admindashboard.css" />

    <style>
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            color: white;
            display: inline-block;
        }

        .status-returned {
            background-color: #4CAF50;
        }

        .table-container {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table-scroll table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-scroll th,
        .table-scroll td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .table-title {
            margin-bottom: 10px;
            font-size: 1.25rem;
            font-weight: bold;
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
                    <h2 class="page-title">Borrowing History</h2>
                </div>
                <div class="topbar-right">
                    <div class="notification">
                        <button>
                            <i class="fas fa-bell"></i>
                        </button>
                        <span class="notification-badge"></span>
                    </div>
                    <div class="mobile-profile">
                        <img class="profile-image" src="/api/placeholder/40/40" alt="Admin profile picture" />
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
                            <a href="dashboard.php" class="sidebar-link">
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
                            <a href="borrowed.php" class="sidebar-link">
                                <i class="fas fa-exchange-alt"></i>
                                <span>Borrowed</span>
                            </a>
                            <a href="overdue.php" class="sidebar-link">
                                <i class="fas fa-clock"></i>
                                <span>Overdue</span>
                            </a>
                            <a href="history.php" class="sidebar-link active">
                                <i class="fas fa-history"></i>
                                <span>History</span>
                            </a>
                            <a href="reports.php" class="sidebar-link">
                                <i class="fas fa-chart-bar"></i>
                                <span>Reports</span>
                            </a>
                            <a href="settings.php" class="sidebar-link">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </nav>
                    </div>
                    <div class="sidebar-footer">
                        <div class="profile">
                            <img class="profile-image" src="/api/placeholder/40/40" alt="Admin profile picture" />
                            <div class="profile-info">
                                <p class="profile-name">John Doe</p>
                                <p class="profile-role">Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Content -->
            <main class="dashboard">
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Borrowing History</h3>
                    </div>
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Borrower</th>
                                    <th>Borrowed Date</th>
                                    <th>Returned Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="history-table-body">
                                <!-- Rows will be inserted by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const historyRecords = [
            {
                title: "The Little Prince",
                borrower: "Anna Cruz",
                borrowedDate: "2025-04-01",
                returnedDate: "2025-04-15",
                status: "returned"
            },
            {
                title: "Frankenstein",
                borrower: "Luke Harris",
                borrowedDate: "2025-03-20",
                returnedDate: "2025-04-04",
                status: "returned"
            },
            {
                title: "Moby Dick",
                borrower: "Eleanor Stone",
                borrowedDate: "2025-03-15",
                returnedDate: "2025-03-29",
                status: "returned"
            }
        ];

        function populateHistoryTable() {
            const tableBody = document.getElementById('history-table-body');
            tableBody.innerHTML = '';

            historyRecords.forEach(record => {
                const row = document.createElement('tr');

                const borrowedDate = new Date(record.borrowedDate).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                });

                const returnedDate = new Date(record.returnedDate).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                });

                row.innerHTML = `
                    <td>${record.title}</td>
                    <td>${record.borrower}</td>
                    <td>${borrowedDate}</td>
                    <td>${returnedDate}</td>
                    <td><span class="status-badge status-returned">Returned</span></td>
                `;
                tableBody.appendChild(row);
            });
        }

        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            document.getElementById('mobile-sidebar').style.display = 'block';
        });

        document.getElementById('close-sidebar').addEventListener('click', () => {
            document.getElementById('mobile-sidebar').style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', () => {
            populateHistoryTable();
        });
    </script>
</body>
</html>
