<?php
require '../components/pdo.php';
require '../components/auth.php';
adminOnly(); 

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'] ?? 'user'; 
    $fname    = trim($_POST['firstname']);
    $lname    = trim($_POST['lastname']);

    // Check if username or email already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->execute([$username, $email]);
    
    if ($check->fetch()) {
        $error = "Username or Email already exists.";
    } else {
        // Hash the password securely
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, email, password_hash, role, firstname, lastname) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$username, $email, $hashed_pass, $role, $fname, $lname]);
            header("Location: admin_users.php?success=created");
            exit;
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #131314; color: #e3e3e3; }
        .card { background-color: #1e1f20; border: 1px solid #444746; }
        .form-control, .form-select { background-color: #131314; border: 1px solid #444746; color: white; }
        .form-control:focus { background-color: #131314; color: white; border-color: #a8c7fa; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card mx-auto p-4" style="max-width: 600px;">
            <h2 class="text-white h4 mb-4">Add New User</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-white form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="text-white form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-white form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="text-white form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-white form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="text-white form-label">Initial Role</label>
                    <select name="role" class="form-select">
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="admin_users.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>