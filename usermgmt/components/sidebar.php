<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current filename to highlight active link
$current_page = basename($_SERVER['PHP_SELF']);

// user is logged in checker
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? 'user';
$fullName = ($_SESSION['firstname'] ?? 'User') . ' ' . ($_SESSION['lastname'] ?? '');
?>
<div class="d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 280px; min-height: 100vh; background-color: #1e1f20; border-right: 1px solid #b1bbb8;">
    <div class="px-3 py-4 mb-3 border-bottom" style="border-color: #444746 !important;">
        <a href="index.php" class="d-flex align-items-center text-white text-decoration-none">
            <span class="fs-4 fw-bold" style="color: #a8c7fa; letter-spacing: -1px;">UserCore</span>
        </a>
        <div class="mt-2 small text-secondary">Management System</div>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="index.php" class="nav-link py-3 px-4 <?= $current_page == 'index.php' ? 'active bg-primary text-white' : 'text-white' ?>" style="border-radius: 0 50px 50px 0; margin-left: -1rem;">
                <i class="bi bi-house-door me-2"></i> Home
            </a>
        </li>

        <?php if ($userRole === 'admin'): ?>
            <li class="nav-item mb-2">
                <a href="admin_users.php" class="nav-link py-3 px-4 <?= ($current_page == 'admin_users.php' || strpos($current_page, 'admin_user_') !== false) ? 'active bg-primary text-white' : 'text-white' ?>" style="border-radius: 0 50px 50px 0; margin-left: -1rem;">
                    <i class="bi bi-people me-2"></i> User Dashboard
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item mb-2">
            <a href="profile.php" class="nav-link py-3 px-4 <?= $current_page == 'profile.php' ? 'active bg-primary text-white' : 'text-white' ?>" style="border-radius: 0 50px 50px 0; margin-left: -1rem;">
                <i class="bi bi-person-circle me-2"></i> My Profile
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="change_password.php" class="nav-link py-3 px-4 <?= $current_page == 'change_password.php' ? 'active bg-primary text-white' : 'text-white' ?>" style="border-radius: 0 50px 50px 0; margin-left: -1rem;">
                <i class="bi bi-shield-lock me-2"></i> Security
            </a>
        </li>
    </ul>

    <hr style="border-color: #444746;">
    
    <div class="px-3 mb-4">
        <div class="small text-secondary mb-2">Signed in as:</div>
        <div class="fw-bold mb-3"><?= htmlspecialchars($fullName) ?></div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 py-2" style="border-radius: 8px;">
            <i class="bi bi-box-arrow-right me-2"></i> Log Out
        </a>
    </div>
</div>

<style>
    .nav-link.active {
        background-color: #a8c7fa !important;
        color: #062e6f !important;
        font-weight: 600;
    }
    .nav-link:hover:not(.active) {
        background-color: #b8bfc7;
    }
</style>