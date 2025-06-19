<?php
require_once '../auth.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = $_POST['nickname'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO author (nickname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nickname, $email, $password);
    $stmt->execute();

    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Penulis - Dashboard Admin</title>
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
            background-color: #ffffff;
            color: #000000;
            line-height: 1.6;
            font-size: 16px;
            padding-top: 35px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Breaking News Banner Style */
        .admin-banner {
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

        header {
            border-top: 4px solid #E60026;
            border-bottom: 1px solid #333333;
            background-color: #ffffff;
            padding: 25px 0;
            margin-bottom: 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            color: #000000;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .subtitle {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.85rem;
            text-align: center;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 400;
        }

        .form-container {
            background-color: #ffffff;
            border: 1px solid #333333;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 25px;
            border-bottom: 2px solid #E60026;
            padding-bottom: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        label {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 15px 12px;
            border: 1px solid #333333;
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 1rem;
            color: #000000;
            background-color: #ffffff;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #E60026;
            border-width: 2px;
            box-shadow: 0 0 8px rgba(230, 0, 38, 0.15);
        }

        button[type="submit"] {
            background-color: #E60026;
            color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 14px 35px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 700;
            border-radius: 2px;
        }

        button[type="submit"]:hover {
            background-color: #cc0022;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
            transform: translateY(-1px);
        }

        .back-section {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #333333;
        }

        .back-link {
            color: #000000;
            text-decoration: none;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid #333333;
        }

        .back-link:hover {
            background-color: #F5F5F5;
            border-color: #E60026;
            color: #E60026;
            transform: translateY(-1px);
        }

        footer {
            margin-top: 60px;
            padding: 30px 0;
            border-top: 4px solid #E60026;
            text-align: center;
            background-color: #F5F5F5;
        }

        .footer-text {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.75rem;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 400;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
                letter-spacing: -0.5px;
            }

            .subtitle {
                font-size: 0.75rem;
                letter-spacing: 2px;
            }

            .form-container {
                padding: 30px 25px;
            }

            .form-title {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            h1 {
                font-size: 1.6rem;
            }

            .form-container {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-banner">ADMIN SYSTEM - TAMBAH PENULIS</div>

    <header>
        <div class="container">
            <h1>Tambah Penulis</h1>
            <div class="subtitle">Content Management System</div>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Form Penulis Baru</h2>
            <form method="POST">
                <label>Nama Penulis:</label>
                <input type="text" name="nickname" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <button type="submit">Simpan Penulis</button>
            </form>
        </div>

        <div class="back-section">
            <a href="list.php" class="back-link">← Kembali ke Daftar</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-text">Admin Dashboard System</div>
        </div>
    </footer>
</body>

</html>