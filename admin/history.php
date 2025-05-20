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

                    <h2 class="page-title">Borrowing History</h2>
            </header>
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
