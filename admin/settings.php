<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Settings - Library Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../public/css/admindashboard.css" />
    <style>
        /* Extra styling for settings form */
        .settings-container {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgb(0 0 0 / 0.1);
        }
        .settings-section {
            margin-bottom: 30px;
        }
        .settings-section h3 {
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .checkbox-group {
            margin-bottom: 15px;
        }
        .checkbox-group label {
            font-weight: 400;
            color: #444;
            margin-left: 8px;
        }
        button.save-btn {
            background-color: #007bff;
            border: none;
            padding: 12px 28px;
            color: white;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button.save-btn:hover {
            background-color: #0056b3;
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

                    <h2 class="page-title">Settings</h2>

            </header>



            <!-- Settings Form -->
            <main class="dashboard">
                <div class="settings-container">
                    <form id="settings-form">
                        <!-- General Settings -->
                        <div class="settings-section">
                            <h3>General Settings</h3>
                            <label for="library-name">Library Name</label>
                            <input type="text" id="library-name" name="libraryName" value="My Library" />

                            <label for="admin-email">Admin Email</label>
                            <input type="email" id="admin-email" name="adminEmail" value="admin@library.com" />
                        </div>

                        <!-- Notifications -->
                        <div class="settings-section">
                            <h3>Notification Preferences</h3>
                            <div class="checkbox-group">
                                <input type="checkbox" id="notify-new-borrow" name="notifyNewBorrow" checked />
                                <label for="notify-new-borrow">Notify on new borrow requests</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="notify-overdue" name="notifyOverdue" checked />
                                <label for="notify-overdue">Notify on overdue books</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="notify-returned" name="notifyReturned" />
                                <label for="notify-returned">Notify when books are returned</label>
                            </div>
                        </div>

                        <!-- Password Change -->
                        <div class="settings-section">
                            <h3>Change Password</h3>
                            <label for="current-password">Current Password</label>
                            <input type="password" id="current-password" name="currentPassword" />

                            <label for="new-password">New Password</label>
                            <input type="password" id="new-password" name="newPassword" />

                            <label for="confirm-password">Confirm New Password</label>
                            <input type="password" id="confirm-password" name="confirmPassword" />
                        </div>

                        <button type="submit" class="save-btn">Save Settings</button>
                    </form>
                </div>
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

        // Simple form submission prevention (no backend)
        document.getElementById('settings-form').addEventListener('submit', e => {
            e.preventDefault();
            alert('Settings saved (front-end only)');
        });
    </script>
</body>
</html>
