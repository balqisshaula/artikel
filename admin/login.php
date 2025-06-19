<?php
session_start();
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&family=Helvetica:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', 'Times', serif;
            background-color: #F5F5F5;
            color: #000000;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Breaking News Banner Style */
        .login-banner {
            background-color: #E60026;
            color: #ffffff;
            text-align: center;
            padding: 8px 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 0 20px;
            margin-top: 35px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 45px;
            background-color: #ffffff;
            padding: 40px 35px 35px;
            border: 1px solid #333333;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .site-title {
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #000000;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.85rem;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 400;
        }

        h2 {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #E60026;
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .error-message {
            background-color: #ffffff;
            border: 2px solid #E60026;
            border-left: 6px solid #E60026;
            color: #E60026;
            padding: 18px 20px;
            margin-bottom: 25px;
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 1rem;
            text-align: left;
            box-shadow: 0 2px 8px rgba(230, 0, 38, 0.15);
        }

        .login-form {
            border: 1px solid #333333;
            background-color: #ffffff;
            padding: 45px 40px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 30px;
        }

        label {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 16px 15px;
            border: 1px solid #333333;
            background-color: #ffffff;
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 1.1rem;
            color: #000000;
            transition: all 0.3s ease;
            border-radius: 1px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #E60026;
            border-width: 2px;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(230, 0, 38, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background-color: #E60026;
            color: #ffffff;
            border: none;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            border-radius: 2px;
        }

        .login-btn:hover {
            background-color: #cc0022;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
            transform: translateY(-1px);
        }

        .login-btn:active {
            background-color: #b30020;
            transform: translateY(0);
        }

        .form-footer {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #333333;
            text-align: center;
        }

        .register-link {
            font-family: 'Times New Roman', 'Times', serif;
            color: #333333;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .register-link:hover {
            text-decoration: underline;
            color: #E60026;
        }

        /* Quote-style highlight for form */
        .login-form {
            position: relative;
        }

        .login-form::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 6px;
            background-color: #E60026;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 0 15px;
            }

            .site-title {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.4rem;
            }

            .login-form {
                padding: 35px 30px;
            }

            .login-header {
                padding: 35px 25px 30px;
            }

            input[type="text"],
            input[type="password"] {
                padding: 14px 12px;
                font-size: 1rem;
            }

            .login-btn {
                padding: 14px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
                padding: 0 10px;
            }

            .site-title {
                font-size: 1.8rem;
            }

            .login-subtitle {
                font-size: 0.75rem;
                letter-spacing: 2px;
            }

            .login-form {
                padding: 30px 20px;
            }

            .login-header {
                padding: 30px 20px 25px;
            }
        }
    </style>
</head>

<body>
    <div class="login-banner">ADMIN PORTAL - SECURE ACCESS ONLY</div>

    <div class="login-container">
        <div class="login-header">
            <div class="site-title">Admin Portal</div>
            <div class="login-subtitle">Content Management System</div>
        </div>

        <div class="login-form">
            <h2>Login Admin</h2>

            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <button type="submit" class="login-btn">Login</button>

                <div class="form-footer">
                    <a href="registrasi.php" class="register-link">Need an account? Register here</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>