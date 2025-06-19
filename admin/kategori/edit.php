<?php
require_once '../auth.php';
require_once '../../config/database.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM category WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) die("Kategori tidak ditemukan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];

    $update = $conn->prepare("UPDATE category SET name = ?, description = ? WHERE id = ?");
    $update->bind_param("ssi", $name, $desc, $id);
    $update->execute();

    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
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
            padding: 35px 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        label {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea {
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 1rem;
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #666666;
            background-color: #ffffff;
            color: #000000;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #E60026;
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(230, 0, 38, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }

        .btn-submit {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #E60026;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 35px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-weight: 700;
            border-radius: 2px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #cc0022;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
            transform: translateY(-1px);
        }

        .back-section {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #333333;
        }

        .back-link {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            color: #333333;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #E60026;
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

            .btn-submit {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .form-container {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-banner">ADMIN SYSTEM - EDIT KATEGORI</div>

    <header>
        <div class="container">
            <h1>Edit Kategori</h1>
            <div class="subtitle">Content Management System</div>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <form method="POST">
                <label>Nama:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

                <label>Deskripsi:</label>
                <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($data['description']) ?></textarea>

                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>

        <div class="back-section">
            <a href="list.php" class="back-link">← Kembali</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-text">Admin Dashboard System</div>
        </div>
    </footer>
</body>

</html>