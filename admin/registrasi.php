<?php
require_once '../config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Konfirmasi password tidak sama.";
    } else {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username sudah terdaftar.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hashed);

            if ($insert->execute()) {
                $success = "Registrasi berhasil. Silakan login.";
            } else {
                $error = "Gagal registrasi.";
            }

            $insert->close();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Registration - AlyNews</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Georgia:wght@400;700&family=Helvetica:wght@400;500;700&family=Times+New+Roman:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #ffffff;
            color: #000000;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background-image:
                linear-gradient(45deg, #F5F5F5 25%, transparent 25%),
                linear-gradient(-45deg, #F5F5F5 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #F5F5F5 75%),
                linear-gradient(-45deg, transparent 75%, #F5F5F5 75%);
            background-size: 60px 60px;
            background-position: 0 0, 0 30px, 30px -30px, -30px 0px;
        }

        .register-container {
            width: 100%;
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 3px solid #000000;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .register-header {
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 40px 30px;
            border-bottom: 4px solid #E60026;
        }

        .site-title {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: #E60026;
            text-transform: uppercase;
            letter-spacing: -0.8px;
            margin-bottom: 8px;
        }

        .register-subtitle {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.9rem;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
        }

        .register-content {
            padding: 50px 40px;
        }

        h2 {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #000000;
            text-align: center;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: #E60026;
        }

        .success-message {
            background-color: #ffffff;
            border: 3px solid #E60026;
            border-left: 6px solid #E60026;
            color: #000000;
            padding: 25px;
            margin-bottom: 30px;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 1rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.1);
        }

        .success-message a {
            color: #E60026;
            text-decoration: none;
            font-weight: 700;
            font-family: 'Helvetica', Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .success-message a:hover {
            text-decoration: underline;
        }

        .error-message {
            background-color: #ffffff;
            border: 3px solid #E60026;
            border-left: 6px solid #E60026;
            color: #E60026;
            padding: 20px;
            margin-bottom: 30px;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 1rem;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.1);
        }

        .register-form {
            border: 2px solid #333333;
            background-color: #F5F5F5;
            padding: 40px 35px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        label {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 16px 18px;
            border: 2px solid #333333;
            background-color: #ffffff;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 1rem;
            color: #000000;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #E60026;
            border-width: 3px;
            background-color: #ffffff;
            box-shadow: 0 0 0 1px #E60026;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #333333;
            font-style: italic;
        }

        .register-btn {
            width: 100%;
            padding: 18px 20px;
            background-color: #E60026;
            color: #ffffff;
            border: 3px solid #E60026;
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            border-radius: 0;
        }

        .register-btn:hover {
            background-color: #ffffff;
            color: #E60026;
            border-color: #E60026;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.2);
        }

        .register-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 6px rgba(230, 0, 38, 0.3);
        }

        .form-footer {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #333333;
            text-align: center;
        }

        .login-link {
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #E60026;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
        }

        .login-link:hover {
            text-decoration: underline;
            color: #000000;
        }

        .password-hint {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 0.85rem;
            color: #333333;
            margin-top: 8px;
            line-height: 1.5;
            font-style: italic;
            border-left: 3px solid #E60026;
            padding-left: 12px;
            background-color: #ffffff;
            padding: 12px;
            margin-top: 10px;
        }

        .security-notice {
            background-color: #000000;
            color: #ffffff;
            padding: 20px;
            margin-top: 30px;
            border-left: 4px solid #E60026;
        }

        .security-notice h4 {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
            color: #E60026;
        }

        .security-notice p {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 0.85rem;
            line-height: 1.5;
            margin: 0;
        }

        @media (max-width: 768px) {
            .register-container {
                margin: 20px;
                max-width: none;
            }

            .site-title {
                font-size: 2.2rem;
            }

            h2 {
                font-size: 1.6rem;
            }

            .register-content {
                padding: 30px 25px;
            }

            .register-form {
                padding: 30px 25px;
            }

            .register-header {
                padding: 30px 20px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 10px;
            }

            .site-title {
                font-size: 1.8rem;
            }

            .register-subtitle {
                font-size: 0.8rem;
                letter-spacing: 1px;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <div class="site-title">AlyNews</div>
            <div class="register-subtitle">Administrative Access Portal</div>
        </div>

        <div class="register-content">
            <h2>Admin Registration</h2>

            <?php if ($success): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                    <br><br>
                    <a href="login.php">Proceed to Login →</a>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!$success): ?>
                <form method="POST" action="" class="register-form">
                    <div class="form-group">
                        <label for="username">Administrator Username:</label>
                        <input type="text" name="username" id="username" required placeholder="Enter unique username">
                    </div>

                    <div class="form-group">
                        <label for="password">Secure Password:</label>
                        <input type="password" name="password" id="password" required placeholder="Create strong password">
                        <div class="password-hint">
                            <strong>Security Requirements:</strong> Use a combination of uppercase and lowercase letters, numbers, and special characters. Minimum 8 characters recommended for optimal security.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm">Confirm Password:</label>
                        <input type="password" name="confirm" id="confirm" required placeholder="Re-enter password">
                    </div>

                    <button type="submit" class="register-btn">Create Admin Account</button>

                    <div class="form-footer">
                        <a href="login.php" class="login-link">Already have administrative access? Sign in here</a>
                    </div>
                </form>

                <div class="security-notice">
                    <h4>Security Notice</h4>
                    <p>This registration creates administrative privileges for content management. Ensure your credentials are kept secure and never shared with unauthorized personnel.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>