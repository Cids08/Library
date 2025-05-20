<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-book-open"></i>
            <span>LibraryAdmin</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <p class="sidebar-section-title">Main</p>
            <a href="AdminDashboard.php" class="sidebar-link <?= $current_page == 'AdminDashboard.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="books.php" class="sidebar-link <?= $current_page == 'books.php' ? 'active' : '' ?>">
                <i class="fas fa-book"></i>
                <span>Books</span>
            </a>
            <a href="members.php" class="sidebar-link <?= $current_page == 'members.php' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Members</span>
            </a>
        </div>
        <div class="sidebar-section">
            <p class="sidebar-section-title">Transactions</p>
            <a href="borrowed.php" class="sidebar-link <?= $current_page == 'borrowed.php' ? 'active' : '' ?>">
                <i class="fas fa-exchange-alt"></i>
                <span>Borrowed</span>
            </a>
            <a href="overdue.php" class="sidebar-link <?= $current_page == 'overdue.php' ? 'active' : '' ?>">
                <i class="fas fa-clock"></i>
                <span>Overdue</span>
            </a>
            <a href="history.php" class="sidebar-link <?= $current_page == 'history.php' ? 'active' : '' ?>">
                <i class="fas fa-history"></i>
                <span>History</span>
            </a>
        </div>
        <div class="sidebar-section">
            <p class="sidebar-section-title">System</p>
            <a href="reports.php" class="sidebar-link <?= $current_page == 'reports.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="settings.php" class="sidebar-link <?= $current_page == 'settings.php' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
    </nav>
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
