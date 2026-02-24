<?php
require '../components/pdo.php';
require '../components/auth.php';
checkLogin(); 

$success = "";
$error = "";

// Fetch current session user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$me = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST['firstname']);
    $lname = trim($_POST['lastname']);
    $gender = $_POST['gender'];
    $natl = trim($_POST['nationality']);
    $contact = trim($_POST['contact_number']);

    // Update ONLY own profile
    $update = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, gender = ?, nationality = ?, contact_number = ? WHERE id = ?");
    
    try {
        $update->execute([$fname, $lname, $gender, $natl, $contact, $_SESSION['user_id']]);
        $success = "Your profile has been updated successfully.";
        // Refresh local variable for immediate UI feedback
        $me['firstname'] = $fname; $me['lastname'] = $lname; 
        $me['gender'] = $gender; $me['nationality'] = $natl; 
        $me['contact_number'] = $contact;
    } catch (PDOException $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | UserCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
    body { 
        color: #ffffff !important; 
    }

    h1, h2, h3, .form-label, .read-only-label { 
        color: #a8c7fa !important; 
        font-weight: 600 !important;
        opacity: 1 !important;
    }

    .card { 
        background-color: #1e1f20; 
        border: 1px solid #444746; 
        border-radius: 12px; 
    }

    .text-secondary {
        color: #c4c7c5 !important; 
    }

    .form-control, .form-select { 
        background-color: #131314; 
        border: 1px solid #444746; 
        color: #ffffff !important; 
    }
    
    .form-control:focus, .form-select:focus { 
        background-color: #131314; 
        color: #ffffff !important; 
        border-color: #a8c7fa; 
        box-shadow: 0 0 0 0.1rem rgba(168, 199, 250, 0.25); 
    }

    .read-only-label { 
        color: #a8c7fa; 
        font-size: 0.85rem; 
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .read-only-value { 
        background-color: #28292a; 
        color: #ffffff !important; 
        padding: 10px 15px; 
        border-radius: 8px; 
        border: 1px solid #444746; 
        font-weight: 500;
    }

    .table {
        color: #ffffff !important;
    }
</style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../components/sidebar.php'; ?>

        <div class="flex-grow-1 p-5" style="background-color: #444746; min-height: 100vh;">
            <div class="card p-5 mx-auto shadow-lg" style="max-width: 900px;">
                <div class="mb-4">
                    <h2 class="h3 mb-1">My Account</h2>
                    <p class="text-secondary">Update your personal information and profile settings.</p>
                </div>

                <?php if($success): ?> <div class="alert alert-success border-0 bg-success text-white py-2"><?= $success ?></div> <?php endif; ?>
                <?php if($error): ?> <div class="alert alert-danger border-0 bg-danger text-white py-2"><?= $error ?></div> <?php endif; ?>

                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <div class="read-only-label mb-2">Username</div>
                        <div class="read-only-value text-secondary">@<?= htmlspecialchars($me['username']) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="read-only-label mb-2">Primary Email</div>
                        <div class="read-only-value text-secondary"><?= htmlspecialchars($me['email']) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="read-only-label mb-2">User Role</div>
                        <div class="read-only-value text-secondary text-capitalize"><?= htmlspecialchars($me['role']) ?></div>
                    </div>
                </div>

                <hr class="mb-5" style="border-color: #444746;">

                <form method="POST">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">First Name</label>
                            <input type="text" name="firstname" class="form-control form-control-lg" value="<?= htmlspecialchars($me['firstname']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Last Name</label>
                            <input type="text" name="lastname" class="form-control form-control-lg" value="<?= htmlspecialchars($me['lastname']) ?>" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Gender</label>
                            <select name="gender" class="form-select form-control-lg">
                                <option value="male" <?= $me['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= $me['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= $me['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small text-secondary">Nationality</label>
                            <input type="text" name="nationality" class="form-control form-control-lg" value="<?= htmlspecialchars($me['nationality']) ?>">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small text-secondary">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control form-control-lg" value="<?= htmlspecialchars($me['contact_number']) ?>">
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #444746;">
                        <a href="change_password.php" class="btn btn-outline-secondary">Security Settings</a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>