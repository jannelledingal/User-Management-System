<?php
require '../components/pdo.php';
require '../components/auth.php';
adminOnly(); 

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: admin_users.php"); exit; }

// Fetch the specific user to edit
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) { die("User not found."); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $role      = $_POST['role'];
    $new_pass  = $_POST['new_password'];

    // Update basic info
    $sql = "UPDATE users SET firstname = ?, lastname = ?, role = ? WHERE id = ?";
    $params = [$firstname, $lastname, $role, $id];
    
    // If a new password was provided, hash it and update
    if (!empty($new_pass)) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET firstname = ?, lastname = ?, role = ?, password = ? WHERE id = ?";
        $params = [$firstname, $lastname, $role, $hashed_pass, $id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    header("Location: admin_users.php?success=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #444746; color: #e3e3e3; }
        .card { background-color: #232338; border: 1px solid #444746; }
        .form-control, .form-select { background-color: #131314; border: 1px solid #444746; color: white; }
        .form-control:focus { background-color: #131314; color: white; border-color: #a8c7fa; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card mx-auto p-4" style="max-width: 600px;">
            <h2 class="text-white mb-4">Edit User: <?= htmlspecialchars($user['username']) ?></h2>
            <form method="POST">
                <div class="row mb-3">
                    <div class="col">
                        <label class="text-white form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($user['firstname']) ?>" required>
                    </div>
                    <div class="col">
                        <label class="text-white form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($user['lastname']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-white form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="text-white form-label">Reset Password (Leave blank to keep current)</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary text-white">Save Changes</button>
                    <a href="admin_users.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>