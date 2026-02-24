<?php
require '../components/pdo.php'; 
require '../components/auth.php'; 
checkLogin(); 

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. Fetch current hashed password using column name from users.sql
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // 2. Validation Logic
    if (!password_verify($current_pass, $user['password_hash'])) { //
        $error = "Current password is incorrect.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "New passwords do not match.";
    } elseif (strlen($new_pass) < 6) {
        $error = "New password must be at least 6 characters.";
    } else {
        // 3. Hash and Update
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $update->execute([$hashed_pass, $_SESSION['user_id']]);
        $success = "Password updated successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - UserCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { 
            background-color: #232338; 
            color: #ffffff !important; 
            font-family: 'Segoe UI', sans-serif;
        }
        .card { 
            background-color: #1e1f20; 
            border: 1px solid #444746; 
            border-radius: 1rem;
        }
        h2, .form-label { 
            color: #a8c7fa !important; 
            font-weight: 600; 
        }
        .form-control { 
            background-color: #131314; 
            border: 1px solid #444746; 
            color: #ffffff !important; 
            border-right: none; 
        }
        .form-control:focus {
            background-color: #131314;
            color: #fff;
            border-color: #a8c7fa;
            box-shadow: none;
        }
        /
        .input-group-text {
            background-color: #a8c7fa !important; 
            border: 1px solid #a8c7fa;
            color: #062e6f !important; 
            cursor: pointer;
            border-radius: 0 0.5rem 0.5rem 0;
        }
        .input-group-text:hover {
            background-color: #c2e7ff !important;
        }
        .btn-primary {
            background-color: #a8c7fa;
            color: #062e6f;
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #c2e7ff;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card mx-auto p-4 shadow-lg" style="max-width: 480px;">
            <h2 class="h4 mb-4 text-center">Security Settings</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 text-center" style="background-color: #370910; color: #f2b8b5; border: 1px solid #f2b8b5;"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success py-2 text-center" style="background-color: #0f2d1e; color: #c4eed0; border: 1px solid #c4eed0;"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="current_pass" class="form-control" required>
                        <span class="input-group-text toggle-password" data-target="current_pass">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>
                
                <hr class="my-4" style="border-color: #444746;">
                
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="new_pass" class="form-control" required>
                        <span class="input-group-text toggle-password" data-target="new_pass">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" name="confirm_password" id="confirm_pass" class="form-control" required>
                        <span class="input-group-text toggle-password" data-target="confirm_pass">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Update Password</button>
                    <a href="profile.php" class="btn btn-outline-secondary text-white border-secondary">Back to Profile</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Multi-field toggle logic
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        });
    </script>
</body>
</html>