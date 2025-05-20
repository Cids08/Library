<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --navy-blue: #0a192f;
            --light-navy: #112240;
            --gray: #8892b0;
            --light-gray: #ccd6f6;
            --white: #e6f1ff;
        }
        
        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }
        
        /* Header Styles */
        header {
            background-color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .logo {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--navy-blue);
        }
        
        .header-title {
            font-size: 1.2rem;
            color: var(--navy-blue);
        }
        
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .admin-profile span {
            color: var(--navy-blue);
            font-weight: 500;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background-color: var(--navy-blue);
            color: var(--white);
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
            padding-top: 4.5rem;
            overflow-y: auto;
        }
        
        .sidebar-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--white);
            margin-bottom: 0.75rem;
        }
        
        .sidebar-profile h3 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .nav-item:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .nav-item.active {
            background-color: var(--gray);
            border-left: 4px solid var(--white);
        }
        
        .nav-item i {
            width: 20px;
            text-align: center;
        }
        
        .submenu {
            margin-left: 2.5rem;
            height: 0;
            overflow: hidden;
            transition: height 0.3s ease;
        }
        
        .submenu-active {
            height: auto;
            padding: 0.5rem 0;
        }
        
        .submenu-item {
            padding: 0.5rem 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .submenu-item:hover {
            color: var(--light-gray);
        }
        
        .submenu-toggle {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        
        .submenu-toggle i.fa-chevron-down {
            transition: transform 0.3s ease;
        }
        
        .rotate-icon {
            transform: rotate(180deg);
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: 260px;
            padding: 5rem 2rem 2rem;
            min-height: 100vh;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            color: var(--gray);
        }
        
        .breadcrumb a {
            color: var(--navy-blue);
            text-decoration: none;
        }
        
        .breadcrumb i {
            font-size: 0.75rem;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
        }
        
        .stat-icon.members {
            background-color: #4caf50;
        }
        
        .stat-icon.books {
            background-color: #2196f3;
        }
        
        .stat-icon.issued {
            background-color: #ff9800;
        }
        
        .stat-icon.fine {
            background-color: #f44336;
        }
        
        .stat-info h3 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            color: var(--navy-blue);
        }
        
        .stat-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .action-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
        }
        
        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
            background-color: var(--navy-blue);
            margin-bottom: 1rem;
        }
        
        .action-card h3 {
            color: var(--navy-blue);
            margin-bottom: 0.5rem;
        }
        
        .action-card p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        /* Responsive Styles */
        @media screen and (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .toggle-sidebar {
                display: block;
            }
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--navy-blue);
            font-size: 1.5rem;
            cursor: pointer;
            display: none;
        }
        
        @media screen and (max-width: 768px) {
            .header-title {
                display: none;
            }
            
            .dashboard-stats, .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .toggle-sidebar {
                display: block;
            }
        }
        
        @media screen and (max-width: 576px) {
            .admin-profile span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo-section">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <span class="logo">LMS</span>
        </div>
        <div class="header-title">Content Control Panel</div>
        <div class="admin-profile">
            <span>John Admin</span>
            <img src="library.jpg" alt="Admin Profile">
        </div>
    </header>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-profile">
            <img src="library.jpg" alt="Admin Profile">
            <h3>John Admin</h3>
            <p>Administrator</p>
        </div>
        <div class="sidebar-nav">
            <div class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-user-graduate"></i>
                <span>All Student Information</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>All Teacher Information</span>
            </div>
            <div class="nav-item">
                <div class="submenu-toggle">
                    <div>
                        <i class="fas fa-book"></i>
                        <span>Manage Books</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="submenu">
                <div class="submenu-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Book</span>
                </div>
                <div class="submenu-item">
                    <i class="fas fa-list"></i>
                    <span>Book List</span>
                </div>
                <div class="submenu-item">
                    <i class="fas fa-edit"></i>
                    <span>Edit Book</span>
                </div>
                <div class="submenu-item">
                    <i class="fas fa-trash"></i>
                    <span>Delete Book</span>
                </div>
            </div>
            <div class="nav-item">
                <div class="submenu-toggle">
                    <div>
                        <i class="fas fa-book-reader"></i>
                        <span>Issue Book</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="submenu">
                <div class="submenu-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Issue New Book</span>
                </div>
                <div class="submenu-item">
                    <i class="fas fa-history"></i>
                    <span>Issued History</span>
                </div>
            </div>
            <div class="nav-item">
                <div class="submenu-toggle">
                    <div>
                        <i class="fas fa-users-cog"></i>
                        <span>Manage Users</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="submenu">
                <div class="submenu-item">
                    <i class="fas fa-user-plus"></i>
                    <span>Add User</span>
                </div>
                <div class="submenu-item">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit User</span>
                </div>
                <div class="submenu-item">
                    <i class="fas fa-user-times"></i>
                    <span>Delete User</span>
                </div>
            </div>
            <div class="nav-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Issued Books</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-clipboard-check"></i>
                <span>View Requested Books</span>
            </div>
            <div class="nav-item">
                <div class="submenu-toggle">
                    <div>
                        <i class="fas fa-envelope"></i>
                        <span>Send Messages to User</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="submenu">
                <div class="submenu-item">
                    <i class="fas fa-paper-plane"></i>
                    <span>New Message</span>
                </div>
                <div class="submenu-item">
                    <i class="fas fa-inbox"></i>
                    <span>Message History</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="breadcrumb">
            <a href="#">Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Dashboard</span>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon members">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>0</h3>
                    <p>Total Members</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon issued">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="stat-info">
                    <h3>0</h3>
                    <p>Issued Books</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon books">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>0</h3>
                    <p>Total Books</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon fine">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>0 Php</h3>
                    <p>Fine Collected</p>
                </div>
            </div>
        </div>
        
        <div class="quick-actions">
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>Manage Books</h3>
                <p>Add, edit, or remove books from the library</p>
            </div>
            
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Manage Users</h3>
                <p>Add, edit, or remove users from the system</p>
            </div>
            
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Status</h3>
                <p>View statistics and reports</p>
            </div>
            
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Requested Books</h3>
                <p>View and manage book requests</p>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        // Setup submenu toggles
        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        submenuToggles.forEach((toggle, index) => {
            toggle.addEventListener('click', function() {
                const chevron = this.querySelector('.fa-chevron-down');
                chevron.classList.toggle('rotate-icon');
                
                const submenu = this.closest('.nav-item').nextElementSibling;
                submenu.classList.toggle('submenu-active');
            });
        });
        
        // Toggle active class on nav items
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                // Don't add active class to submenu toggle items
                if (!this.querySelector('.submenu-toggle')) {
                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>