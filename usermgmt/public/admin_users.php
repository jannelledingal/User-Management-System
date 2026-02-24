<?php
require '../components/pdo.php';
require '../components/auth.php';
adminOnly(); 

// Handle Search and Filters
$search = trim($_GET['search'] ?? '');
$role_filter = $_GET['role_filter'] ?? '';

$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (username LIKE ? OR email LIKE ? OR firstname LIKE ? OR lastname LIKE ?)";
    $term = "%$search%";
    array_push($params, $term, $term, $term, $term);
}

if ($role_filter !== '') {
    $query .= " AND role = ?";
    $params[] = $role_filter;
}

$query .= " ORDER BY id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | UserCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { color: #e3e3e3; overflow-x: hidden; }
        .card { background-color: #232338; border: 1px solid #444746; border-radius: 12px; }
        .table { color: #e3e3e3; border-color: #444746; }
        .table thead th { border-top: none; color: #a8c7fa; font-weight: 500; }
        .form-control, .form-select { background-color: #1e1f20; border: 1px solid #444746; color: white; }
        .form-control:focus, .form-select:focus { background-color: #1e1f20; color: white; border-color: #a8c7fa; box-shadow: none; }
        .badge-admin { background-color: #004a77; color: #c2e7ff; }
        .badge-user { background-color: #333537; color: #e3e3e3; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../components/sidebar.php'; ?>

        <div class="flex-grow-1 p-5" style="background-color: #444746; min-height: 100vh;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">User Management</h1>
                    <p class="text-white small">Viewing all <?= count($users) ?> users in the system.</p>
                </div>
                <a href="admin_user_create.php" class="btn btn-primary px-4">+ Add New User</a>
            </div>

            <div class="card p-3 mb-4">
                <form class="row g-3">
                    <div class="col-md-7">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or username..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="role_filter" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin" <?= $role_filter == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= $role_filter == 'user' ? 'selected' : '' ?>>User</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Apply Filter</button>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Name</th>
                                <th>Username / Email</th>
                                <th>Role</th>
                                <th>Contact & Nationality</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr class="align-middle">
                                <td class="ps-4 text-secondary"><?= $u['id'] ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($u['firstname'].' '.$u['lastname']) ?></div>
                                    <div class="small text-secondary text-capitalize"><?= $u['gender'] ?></div>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($u['username']) ?></div>
                                    <div class="small text-secondary"><?= htmlspecialchars($u['email']) ?></div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill <?= $u['role'] == 'admin' ? 'badge-admin' : 'badge-user' ?>">
                                        <?= ucfirst($u['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small"><?= htmlspecialchars($u['nationality'] ?? 'N/A') ?></div>
                                    <div class="small text-secondary"><?= htmlspecialchars($u['contact_number'] ?? 'No Contact') ?></div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="admin_user_edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <a href="admin_user_delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Permanently delete this user?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>