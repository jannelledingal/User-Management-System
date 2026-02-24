<?php
session_start();
// Check if user is logged in to customize the view
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? null;
$firstName = $_SESSION['firstname'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UserCore | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #232338; color: #e3e3e3; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .hero-section { min-height: 80vh; display: flex; align-items: center; }
        .card { background-color: #1e1f20; border: 1px solid #444746; border-radius: 1rem; }
        .btn-primary { background-color: #a8c7fa; color: #062e6f; border: none; font-weight: 600; }
        .btn-primary:hover { background-color: #c2e7ff; color: #062e6f; }
        .btn-outline-light { border-color: #444746; color: #e3e3e3; }
        .btn-outline-light:hover { background-color: #333537; }
        .feature-icon { color: #a8c7fa; font-size: 2rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark pt-4">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#">UserCore</a>
                <div class="d-flex">
                    <?php if ($isLoggedIn): ?>
                        <span class="navbar-text me-3">Welcome, <strong><?= htmlspecialchars($firstName) ?></strong></span>
                        <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-sm btn-primary">Sign In</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <section class="hero-section">
            <div class="row w-100">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Manage your community <span style="color: #a8c7fa;">securely.</span></h1>
                    <p class="lead text-secondary mb-5">A User Management System built with PHP PDO, featuring granular role-based access control and with database.</p>
                    
                    <div class="d-flex gap-3">
                        <?php if (!$isLoggedIn): ?>
                            <a href="login.php" class="btn btn-lg btn-primary px-4">Get Started</a>
                        <?php else: ?>
                            <?php if ($userRole === 'admin'): ?>
                                <a href="admin_users.php" class="btn btn-lg btn-primary px-4">Admin Dashboard</a>
                            <?php else: ?>
                                <a href="profile.php" class="btn btn-lg btn-primary px-4">My Profile</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                    
                </div>
            </div>
        </section>
    </div>
</body>
</html>