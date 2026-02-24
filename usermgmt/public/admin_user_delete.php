<?php
require '../components/pdo.php';
require '../components/auth.php';
adminOnly();

$id = $_GET['id'] ?? null;

// Prevent admin from deleting themselves
if ($id == $_SESSION['user_id']) {
    header("Location: admin_users.php?error=cannot_delete_self");
    exit;
}

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_users.php?success=deleted");
exit;