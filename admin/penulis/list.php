<?php
require_once '../auth.php';
require_once '../../config/database.php';

$result = $conn->query("SELECT * FROM author ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Penulis - Dashboard Admin</title>
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
            max-width: 1200px;
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
            font-size: 3rem;
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

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #333333;
        }

        .section-title {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #000000;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .add-button {
            background-color: #E60026;
            color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 14px 35px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 700;
            border-radius: 2px;
            display: inline-block;
        }

        .add-button:hover {
            background-color: #cc0022;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
            transform: translateY(-1px);
        }

        .table-container {
            background-color: #ffffff;
            border: 1px solid #333333;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #000000;
            color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 15px;
            text-align: left;
            font-weight: 700;
            border-bottom: 2px solid #E60026;
        }

        td {
            padding: 18px 15px;
            border-bottom: 1px solid #E5E5E5;
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 1rem;
            color: #000000;
        }

        tr:nth-child(even) {
            background-color: #F9F9F9;
        }

        tr:hover {
            background-color: #F5F5F5;
        }

        .id-column {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-weight: 700;
            color: #666666;
            width: 80px;
            text-align: center;
        }

        .action-links {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.85rem;
        }

        .action-links a {
            color: #E60026;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            transition: all 0.3s ease;
            padding: 5px 8px;
            margin-right: 8px;
            border: 1px solid transparent;
        }

        .action-links a:hover {
            background-color: #F5F5F5;
            border-color: #E60026;
            transform: translateY(-1px);
        }

        .action-links a.delete {
            color: #d32f2f;
        }

        .action-links a.delete:hover {
            border-color: #d32f2f;
        }

        .back-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #333333;
            text-align: center;
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
            padding: 12px 25px;
            border: 1px solid #333333;
        }

        .back-link:hover {
            background-color: #F5F5F5;
            border-color: #E60026;
            color: #E60026;
            transform: translateY(-1px);
        }

        footer {
            margin-top: 80px;
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
                font-size: 2.2rem;
                letter-spacing: -0.5px;
            }

            .subtitle {
                font-size: 0.75rem;
                letter-spacing: 2px;
            }

            .action-bar {
                flex-direction: column;
                gap: 20px;
                align-items: stretch;
                text-align: center;
            }

            .section-title {
                font-size: 1.2rem;
            }

            th,
            td {
                padding: 15px 10px;
                font-size: 0.9rem;
            }

            .action-links a {
                display: block;
                margin: 5px 0;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            h1 {
                font-size: 1.8rem;
            }

            th,
            td {
                padding: 12px 8px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-banner">ADMIN SYSTEM - MANAJEMEN PENULIS</div>

    <header>
        <div class="container">
            <h1>Data Penulis</h1>
            <div class="subtitle">Content Management System</div>
        </div>
    </header>

    <div class="container">
        <div class="action-bar">
            <h2 class="section-title">Daftar Penulis</h2>
            <a href="create.php" class="add-button">+ Tambah Penulis</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Penulis</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="id-column"><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nickname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td class="action-links">
                                <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Hapus penulis ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="back-section">
            <a href="../index.php" class="back-link">← Kembali ke Dashboard</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-text">Admin Dashboard System</div>
        </div>
    </footer>
</body>

</html>