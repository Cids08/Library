<?php
// Include the database connection
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get filter and pagination values
    $subject_filter = $_GET['subject'] ?? '';
    $page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
    $limit = 8; // Books per page
    $offset = ($page - 1) * $limit;

    // Count total filtered books
    $count_query = "SELECT COUNT(*) FROM books WHERE available = 1";
    if (!empty($subject_filter)) {
        $count_query .= " AND subject = ?";
    }
    $stmt = $pdo->prepare($count_query);
    if (!empty($subject_filter)) {
        $stmt->execute([$subject_filter]);
    } else {
        $stmt->execute();
    }
    $total_books = $stmt->fetchColumn();
    $total_pages = ceil($total_books / $limit);

    // Fetch books with optional subject filter
    $query = "SELECT * FROM books WHERE available = 1";
    if (!empty($subject_filter)) {
        $query .= " AND subject = ?";
    }
    $query .= " ORDER BY title ASC LIMIT ? OFFSET ?";
    
    $stmt = $pdo->prepare($query);
    if (!empty($subject_filter)) {
        $stmt->bindValue(1, $subject_filter);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}


// Page title
$pageTitle = "Members Management";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Members Management - Library Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../public/css/admindashboard.css" />
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #eef2ff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --red-500: #ef4444;
            --yellow-500: #eab308;
            --green-500: #22c55e;
            --box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
            --border-radius: 8px;
        }

        /* General Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .container {
            display: flex;
        }

        .main-content {
            flex: 1;
            margin-left: 0;
            min-height: 100vh;
            transition: var(--transition);
            width: 100%;
        }

        /* Topbar */
        .topbar {
            background-color: white;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-button {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--gray-700);
            cursor: pointer;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification button {
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: var(--transition);
        }

        .notification button:hover {
            background-color: var(--gray-100);
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            background-color: var(--red-500);
            border-radius: 50%;
        }

        .mobile-profile {
            display: none;
        }

        /* Dashboard Content */
        .dashboard {
            padding: 1.5rem;
        }

        /* Page Actions */
        .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        .search-input {
            flex: 1;
            max-width: 400px;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            transition: var(--transition);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        th,
        td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        tr:hover {
            background-color: var(--gray-50);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(34, 197, 94, 0.1);
            color: var(--green-500);
        }

        .status-inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--red-500);
        }

        .action-button {
            background: none;
            border: none;
            padding: 0.4rem;
            margin-right: 0.25rem;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
        }

        .action-button:hover {
            background-color: var(--gray-100);
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-top: 1px solid var(--gray-200);
            background-color: var(--gray-50);
        }

        .pagination {
            display: flex;
            gap: 0.25rem;
        }

        .pagination-button {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            background-color: white;
            color: var(--gray-700);
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
        }

        .pagination-button:hover:not([disabled]) {
            background-color: var(--gray-100);
        }

        /* Improved Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
        }

        .modal-content {
            background-color: white;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modal-appear 0.3s ease-out;
            position: relative;
            overflow: hidden;
        }

        @keyframes modal-appear {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-button {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--gray-500);
            cursor: pointer;
            padding: 0.35rem;
            border-radius: 50%;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            z-index: 51;
        }

        .close-button:hover {
            color: var(--gray-800);
            background-color: var(--gray-100);
        }

        .modal-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        form {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group:last-of-type {
            margin-bottom: 2rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            transition: var(--transition);
            background-color: white;
            color: var(--gray-800);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        input::placeholder {
            color: var(--gray-400);
        }

        /* Updated button styling in the modal */
        .modal .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Modal footer */
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 1rem 1.5rem;
            background-color: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        /* Cancel button option */
        .btn-outline {
            background-color: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            margin-right: 0.75rem;
        }

        .btn-outline:hover {
            background-color: var(--gray-50);
            border-color: var(--gray-400);
        }

        /* Required field indicator */
        .required-field::after {
            content: " *";
            color: var(--red-500);
        }

        /* Loading spinner */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .spinner {
            border: 3px solid var(--gray-200);
            border-radius: 50%;
            border-top: 3px solid var(--primary-color);
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Empty state */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.125rem;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--gray-500);
            max-width: 400px;
            margin: 0 auto;
        }

        /* Responsive modal */
        @media (max-width: 640px) {
            .modal-content {
                width: 95%;
                max-height: 90vh;
                overflow-y: auto;
            }

            .modal-footer {
                position: sticky;
                bottom: 0;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .menu-button {
                display: block;
            }
            .mobile-profile {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .page-actions {
                flex-direction: column;
                align-items: stretch;
            }
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include '../includes/adminsidebar.php'; ?>
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-button" id="mobile-menu-button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="page-title"><?php echo $pageTitle; ?></h2>
                </div>
                <div class="topbar-right">
                    <div class="notification">
                        <button>
                            <i class="fas fa-bell"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard">
                <!-- Page Actions -->
                <div class="page-actions">
                    <button class="btn btn-primary" id="add-member-btn">
                        <i class="fas fa-plus"></i> Add New Member
                    </button>
                    <input type="text" id="search-member" class="search-input" placeholder="Search members..." />
                </div>
                
                <!-- Table Container -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Membership Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="members-table-body">
                            <!-- Member data will be populated by JavaScript -->
                            <tr>
                                <td colspan="6" class="loading">
                                    <div class="spinner"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="table-footer">
                        <div id="table-info">Loading members data...</div>
                        <div class="pagination">
                            <button class="pagination-button" id="prev-page" disabled>
                                <i class="fas fa-chevron-left"></i> Prev
                            </button>
                            <button class="pagination-button" id="next-page" disabled>
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Improved Modal -->
            <div id="member-modal" class="modal">
                <div class="modal-content">
                    <h3 id="modal-title">Add Member</h3>
                    <button class="close-button" id="close-member-modal"><i class="fas fa-times"></i></button>
                    <form id="member-form">
                        <input type="hidden" id="member-id" name="memberId" value="" />
                        <div class="form-group">
                            <label for="member-name" class="required-field">Full Name</label>
                            <input type="text" id="member-name" name="memberName" placeholder="Enter member name" required />
                        </div>
                        <div class="form-group">
                            <label for="member-email" class="required-field">Email</label>
                            <input type="email" id="member-email" name="memberEmail" placeholder="Enter email address" required />
                        </div>
                        <div class="form-group">
                            <label for="member-phone">Phone Number</label>
                            <input type="tel" id="member-phone" name="memberPhone" placeholder="Enter phone number" />
                        </div>
                        <div class="form-group">
                            <label for="member-status" class="required-field">Status</label>
                            <select id="member-status" name="memberStatus" required>
                                <option value="">Select status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline" id="cancel-member-btn">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="save-member-btn">Save Member</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="delete-modal" class="modal">
                <div class="modal-content">
                    <h3>Confirm Delete</h3>
                    <button class="close-button" id="close-delete-modal"><i class="fas fa-times"></i></button>
                    <form id="delete-form">
                        <input type="hidden" id="delete-member-id" name="deleteMemberId" value="" />
                        <div style="padding: 1rem 1.5rem;">
                            <p>Are you sure you want to delete this member? This action cannot be undone.</p>
                            <p id="delete-member-name" style="font-weight: 600;"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline" id="cancel-delete-btn">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="background-color: var(--red-500);">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentPage = 1;
        let totalPages = 1;
        let searchQuery = '';
        let membersData = [];

        // DOM elements
        const membersTableBody = document.getElementById('members-table-body');
        const tableInfo = document.getElementById('table-info');
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const searchInput = document.getElementById('search-member');
        const addMemberBtn = document.getElementById('add-member-btn');
        const memberModal = document.getElementById('member-modal');
        const modalTitle = document.getElementById('modal-title');
        const memberForm = document.getElementById('member-form');
        const memberId = document.getElementById('member-id');
        const closeModalBtn = document.getElementById('close-member-modal');
        const cancelBtn = document.getElementById('cancel-member-btn');
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');
        const deleteMemberId = document.getElementById('delete-member-id');
        const deleteMemberName = document.getElementById('delete-member-name');
        const closeDeleteModalBtn = document.getElementById('close-delete-modal');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.querySelector('.sidebar');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Load members data
            loadMembers();

            // Set up event listeners
            setupEventListeners();
        });

        // Load members with pagination and search
        function loadMembers() {
            // Show loading spinner
            membersTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="loading">
                        <div class="spinner"></div>
                    </td>
                </tr>
            `;

            // Build API URL with pagination and search
            let url = `api/members.php?page=${currentPage}&limit=10`;
            if (searchQuery) {
                url += `&search=${encodeURIComponent(searchQuery)}`;
            }

            // Fetch members data
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        membersData = data.data;
                        totalPages = data.pagination.totalPages;
                        renderMembers(membersData);
                        updatePagination(data.pagination);
                    } else {
                        showError('Failed to load members');
                    }
                })
                .catch(error => {
                    console.error('Error loading members:', error);
                    showError('Error loading members. Please try again.');
                });
        }

        // Render members data to table
        function renderMembers(members) {
            if (members.length === 0) {
                // Show empty state
                membersTableBody.innerHTML = `
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <h3>No members found</h3>
                                <p>There are no members matching your search or you haven't added any members yet.</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            // Generate table rows
            let html = '';
            members.forEach(member => {
                const statusClass = member.status === 'active' ? 'status-active' : 'status-inactive';
                const formattedDate = new Date(member.membershipDate).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });

                html += `
                    <tr data-id="${member.id}">
                        <td>${member.name}</td>
                        <td>${member.email}</td>
                        <td>${member.phone || '-'}</td>
                        <td>${formattedDate}</td>
                        <td><span class="status-badge ${statusClass}">${member.status}</span></td>
                        <td>
                            <button class="action-button edit-member" title="Edit member">
                                <i class="fas fa-edit text-blue-600"></i>
                            </button>
                            <button class="action-button delete-member" title="Delete member">
                                <i class="fas fa-trash text-red-600"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            membersTableBody.innerHTML = html;

            // Add event listeners to edit and delete buttons
            document.querySelectorAll('.edit-member').forEach(button => {
                button.addEventListener('click', handleEditMember);
            });

            document.querySelectorAll('.delete-member').forEach(button => {
                button.addEventListener('click', handleDeleteMember);
            });
        }

        // Update pagination controls
        function updatePagination(pagination) {
            const { total, page, limit, totalPages } = pagination;
            const start = ((page - 1) * limit) + 1;
            const end = Math.min(start + limit - 1, total);

            // Update table info
            tableInfo.textContent = `Showing ${start} to ${end} of ${total} members`;

            // Update pagination buttons
            prevPageBtn.disabled = page <= 1;
            nextPageBtn.disabled = page >= totalPages;

            // Store current page
            currentPage = page;
        }

        // Show error message in table
        function showError(message) {
            membersTableBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--red-500);">
                        <i class="fas fa-exclamation-circle" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <p>${message}</p>
                    </td>
                </tr>
            `;
        }

        // Set up all event listeners
        function setupEventListeners() {
            // Pagination
            prevPageBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    loadMembers();
                }
            });

            nextPageBtn.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadMembers();
                }
            });

            // Search
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = searchInput.value.trim();
                    currentPage = 1; // Reset to first page on search
                    loadMembers();
                }, 500);
            });

            // Add member
            addMemberBtn.addEventListener('click', () => {
                openAddMemberModal();
            });

            // Close modal
            closeModalBtn.addEventListener('click', () => {
                memberModal.style.display = 'none';
            });

            cancelBtn.addEventListener('click', () => {
                memberModal.style.display = 'none';
            });

            // Close delete modal
            closeDeleteModalBtn.addEventListener('click', () => {
                deleteModal.style.display = 'none';
            });

            cancelDeleteBtn.addEventListener('click', () => {
                deleteModal.style.display = 'none';
            });

            // Submit member form
            memberForm.addEventListener('submit', handleFormSubmit);

            // Submit delete form
            deleteForm.addEventListener('submit', handleDeleteSubmit);

            // Mobile menu toggle
            mobileMenuButton.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            // Close modal when clicking outside
            window.addEventListener('click', (event) => {
                if (event.target === memberModal) {
                    memberModal.style.display = 'none';
                }
                if (event.target === deleteModal) {
                    deleteModal.style.display = 'none';
                }
            });
        }

        // Open add member modal
        function openAddMemberModal() {
            // Clear form
            memberForm.reset();
            memberId.value = '';
            modalTitle.textContent = 'Add Member';
            
            // Open modal
            memberModal.style.display = 'flex';
        }

        // Open edit member modal
        function openEditMemberModal(member) {
            // Fill form with member data
            memberId.value = member.id;
            document.getElementById('member-name').value = member.name;
            document.getElementById('member-email').value = member.email;
            document.getElementById('member-phone').value = member.phone || '';
            document.getElementById('member-status').value = member.status;
            
            // Update modal title
            modalTitle.textContent = 'Edit Member';
            
            // Open modal
            memberModal.style.display = 'flex';
        }

        // Handle edit member button click
        function handleEditMember(event) {
            const row = event.target.closest('tr');
            const memberId = row.dataset.id;
            const member = membersData.find(m => m.id == memberId);
            
            if (member) {
                openEditMemberModal(member);
            }
        }

        // Handle delete member button click
        function handleDeleteMember(event) {
            const row = event.target.closest('tr');
            const memberId = row.dataset.id;
            const member = membersData.find(m => m.id == memberId);
            
            if (member) {
                // Set values for delete modal
                document.getElementById('delete-member-id').value = member.id;
                document.getElementById('delete-member-name').textContent = member.name;
                
                // Open delete modal
                deleteModal.style.display = 'flex';
            }
        }

        // Handle form submission (add/edit member)
        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Get form data
            const formData = {
                id: memberId.value,
                name: document.getElementById('member-name').value,
                email: document.getElementById('member-email').value,
                phone: document.getElementById('member-phone').value,
                status: document.getElementById('member-status').value
            };
            
            // Send API request
            fetch('api/members.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    memberModal.style.display = 'none';
                    
                    // Reload members data
                    loadMembers();
                    
                    // Show success message
                    alert(data.message);
                } else {
                    // Show error message
                    alert(data.message || 'Error saving member');
                }
            })
            .catch(error => {
                console.error('Error saving member:', error);
                alert('Error saving member. Please try again.');
            });
        }

        // Handle delete form submission
        function handleDeleteSubmit(event) {
            event.preventDefault();
            
            const id = document.getElementById('delete-member-id').value;
            
            // Send API request
            fetch('api/members.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    deleteModal.style.display = 'none';
                    
                    // Reload members data
                    loadMembers();
                    
                    // Show success message
                    alert(data.message);
                } else {
                    // Show error message
                    alert(data.message || 'Error deleting member');
                }
            })
            .catch(error => {
                console.error('Error deleting member:', error);
                alert('Error deleting member. Please try again.');
            });
        }
    </script>
</body>
</html>