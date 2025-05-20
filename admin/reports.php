<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Report Management - Library Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../public/css/admindashboard.css" />
    <style>
        /* Additional styling for report cards */
        .reports-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .report-card {
            background: #fff;
            box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
            padding: 20px;
            border-radius: 8px;
            flex: 1 1 300px;
        }
        .report-card h3 {
            margin-bottom: 15px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
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
                
                    <h2 class="page-title">Report Management</h2>

                
            </header>


            <!-- Reports Content -->
            <main class="dashboard">
                <div class="reports-container">
                    <!-- Most Borrowed Books -->
                    <div class="report-card">
                        <h3>Most Borrowed Books</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Times Borrowed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>The Great Gatsby</td><td>15</td></tr>
                                <tr><td>1984</td><td>12</td></tr>
                                <tr><td>To Kill a Mockingbird</td><td>10</td></tr>
                                <tr><td>Pride and Prejudice</td><td>9</td></tr>
                                <tr><td>Harry Potter and the Sorcerer's Stone</td><td>8</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Active Members -->
                    <div class="report-card">
                        <h3>Active Members</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Books Borrowed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Emma Johnson</td><td>7</td></tr>
                                <tr><td>David Brown</td><td>5</td></tr>
                                <tr><td>Sarah Williams</td><td>4</td></tr>
                                <tr><td>Robert Wilson</td><td>3</td></tr>
                                <tr><td>Linda Green</td><td>2</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Overdue Summary -->
                    <div class="report-card">
                        <h3>Overdue Summary</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Currently Overdue</td><td>4</td></tr>
                                <tr><td>Returned Late</td><td>2</td></tr>
                                <tr><td>Lost Books</td><td>1</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Optionally add more report sections here -->
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            document.getElementById('mobile-sidebar').style.display = 'block';
        });
        document.getElementById('close-sidebar').addEventListener('click', () => {
            document.getElementById('mobile-sidebar').style.display = 'none';
        });
    </script>
</body>
</html>
