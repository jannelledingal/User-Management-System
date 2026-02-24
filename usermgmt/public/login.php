<?php
require '../components/pdo.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginInput = trim($_POST['login_input']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$loginInput, $loginInput]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        
        $user['role'] === 'admin' ? header("Location: admin_users.php") : header("Location: profile.php");
        exit;
    } else {
        $error = "Invalid credentials. Please check your username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | UserCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { 
            background-color: #131314; 
            color: #ffffff; 
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
            opacity: 1 !important; 
        }
        
        .text-white-50 {
            color: #c4c7c5 !important; 
            opacity: 1 !important;
        }

        .alert-danger {
            background-color: #370910;
            border: 1px solid #f2b8b5;
            color: #f2b8b5; 
            font-weight: 500;
        }

        .form-control { 
            background-color: #131314; 
            border: 1px solid #444746; 
            color: #ffffff !important; 
        }
        .form-control:focus {
            background-color: #131314;
            color: #fff;
            border-color: #a8c7fa;
            box-shadow: 0 0 0 0.25rem rgba(168, 199, 250, 0.25);
        }

        .input-group-text {
            background-color: #a8c7fa !important; 
            border: 1px solid #a8c7fa;
            color: #062e6f !important; 
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .input-group-text:hover {
            background-color: #c2e7ff !important; 
            color: #062e6f !important;
        }

        .input-group .form-control {
            border-right: none;
        }

        .btn-primary {
            background-color: #a8c7fa;
            color: #062e6f;
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #c2e7ff;
            color: #062e6f;
        }
    </style>
</head>
<body class="d-flex align-items-center vh-100">
    <div class="container">
        <div class="card mx-auto p-4 p-md-5 shadow-lg" style="max-width: 450px;">
            <div class="text-center mb-4">
                <h2 class="h3">UserCore</h2>
                <p class="text-white-50 small">Sign in to manage your account</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger mb-4 py-2 text-center" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username or Email</label>
                    <input type="text" name="login_input" class="form-control form-control-lg" required autofocus>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordInput" class="form-control form-control-lg" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Sign In</button>
                <div class="text-center">
                    <a href="index.php" class="text-decoration-none small" style="color: #a8c7fa;">Back to Home</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');
        const toggleIcon = document.querySelector('#toggleIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>