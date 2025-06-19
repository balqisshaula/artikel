<?php
session_start();
require_once 'config/database.php';

// // Redirect jika sudah login
// if (isset($_SESSION['user_id'])) {
//     if ($_SESSION['role'] === 'admin') {
//         header('Location: admin/index.php');
//     } else {
//         header('Location: public/index.php');
//     }
//     exit();
// }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        // Query untuk mencari user
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verifikasi password (tanpa hash)
            if ($password === $user['password']) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: public/index.php');
                }
                exit();
            } else {
                $error = 'Username atau password salah';
            }
        } else {
            $error = 'Username atau password salah';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Content Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&family=Helvetica:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000;
        }

        .login-container {
            background-color: #ffffff;
            border: 3px solid #E60026;
            box-shadow: 0 10px 40px rgba(230, 0, 38, 0.15);
            width: 100%;
            max-width: 450px;
            padding: 50px 40px;
            position: relative;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #E60026;
            padding-bottom: 25px;
        }

        .login-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }

        .login-subtitle {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.8rem;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 400;
        }

        .login-form {
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: #000000;
            display: block;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-input {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid #333333;
            background-color: #ffffff;
            font-family: 'Times New Roman', Times, serif;
            font-size: 1rem;
            color: #000000;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #E60026;
            box-shadow: 0 0 0 3px rgba(230, 0, 38, 0.1);
        }

        .form-input:hover {
            border-color: #E60026;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background-color: #E60026;
            color: #ffffff;
            border: none;
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            background-color: #cc0022;
            box-shadow: 0 6px 20px rgba(230, 0, 38, 0.3);
            transform: translateY(-2px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-message {
            background-color: #ffebee;
            border: 2px solid #E60026;
            color: #E60026;
            padding: 15px;
            margin-bottom: 25px;
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .demo-info {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #333333;
            text-align: center;
        }

        .demo-title {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            color: #333333;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .demo-credentials {
            font-family: 'Times New Roman', Times, serif;
            font-size: 0.85rem;
            color: #666666;
            line-height: 1.6;
        }

        .demo-credentials strong {
            color: #E60026;
            font-weight: 700;
        }

        .brand-banner {
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            height: 6px;
            background: linear-gradient(90deg, #E60026 0%, #cc0022 50%, #E60026 100%);
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 35px 30px;
            }

            .login-title {
                font-size: 1.8rem;
            }

            .login-subtitle {
                font-size: 0.75rem;
                letter-spacing: 1.5px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="brand-banner"></div>

        <div class="login-header">
            <h1 class="login-title">CMS Login</h1>
            <div class="login-subtitle">Content Management System</div>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-input"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="demo-info">
            <div class="demo-title">Demo Credentials</div>
            <div class="demo-credentials">
                <strong>Admin:</strong> admin / admin123<br>
                <strong>Public:</strong> user / user123
            </div>
        </div>
    </div>
</body>

</html>