<?php
require_once '../auth.php';
require_once '../../config/database.php';

$sql = "SELECT * FROM article ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Artikel</title>
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

        .content-section {
            margin-bottom: 40px;
        }

        .add-button {
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
            margin-bottom: 30px;
        }

        .add-button:hover {
            background-color: #cc0022;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
            transform: translateY(-1px);
        }

        .table-container {
            border: 1px solid #333333;
            background-color: #ffffff;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Times New Roman', 'Times', serif;
        }

        th {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #000000;
            color: #ffffff;
            padding: 18px 15px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            font-weight: 700;
            border-right: 1px solid #333333;
        }

        th:last-child {
            border-right: none;
        }

        td {
            padding: 18px 15px;
            border-bottom: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            vertical-align: top;
            font-size: 1rem;
        }

        td:last-child {
            border-right: none;
        }

        tr:nth-child(even) {
            background-color: #F5F5F5;
        }

        tr:hover {
            background-color: #f0f0f0;
            border-color: #E60026;
        }

        .article-title {
            font-weight: 600;
            color: #000000;
            max-width: 300px;
            word-wrap: break-word;
            font-family: 'Times New Roman', 'Times', serif;
        }

        .article-image img {
            border: 1px solid #333333;
            display: block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .no-image {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #666666;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 400;
        }

        .status {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 6px 12px;
            color: #ffffff;
            font-weight: 700;
            border-radius: 2px;
        }

        .status.published {
            background-color: #2d5016;
        }

        .status.draft {
            background-color: #E60026;
        }

        .actions {
            white-space: nowrap;
        }

        .actions a {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #E60026;
            text-decoration: none;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .actions a:hover {
            border-bottom-color: #E60026;
            color: #cc0022;
        }

        .actions a.delete {
            color: #cc0000;
        }

        .actions a.delete:hover {
            border-bottom-color: #cc0000;
            color: #990000;
        }

        .separator {
            color: #666666;
            margin: 0 10px;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        .back-link {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid #333333;
            text-align: center;
        }

        .back-link a {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #333333;
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
        }

        .back-link a:hover {
            background-color: #E60026;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
            transform: translateY(-1px);
        }

        .article-date {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            font-size: 0.9rem;
            font-weight: 400;
        }

        .row-number {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            font-weight: 700;
            text-align: center;
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
                font-size: 2rem;
                letter-spacing: -0.5px;
            }

            .subtitle {
                font-size: 0.75rem;
                letter-spacing: 2px;
            }

            .table-container {
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 12px 10px;
            }

            .article-title {
                max-width: 200px;
            }

            .add-button,
            .back-link a {
                padding: 12px 25px;
                font-size: 0.8rem;
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
                padding: 10px 8px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-banner">ADMIN SYSTEM - ARTICLE MANAGEMENT</div>

    <header>
        <div class="container">
            <h1>Data Artikel</h1>
            <div class="subtitle">Article Management System</div>
        </div>
    </header>

    <div class="container">
        <div class="content-section">
            <a href="create.php" class="add-button">+ Tambah Artikel</a>

            <div class="table-container">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Gambar</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <?php $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="row-number"><?= $no++ ?></td>
                            <td class="article-title"><?= htmlspecialchars($row['title']) ?></td>
                            <td class="article-image">
                                <?php if ($row['picture']): ?>
                                    <img src="../../assets/images/<?= $row['picture'] ?>" width="80" height="60" style="object-fit: cover;">
                                <?php else: ?>
                                    <span class="no-image">(Tidak ada)</span>
                                <?php endif; ?>
                            </td>
                            <td class="article-date"><?= $row['date'] ?></td>
                            <td>
                                <span class="status <?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
                            </td>
                            <td class="actions">
                                <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                                <span class="separator">|</span>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <div class="back-link">
            <a href="../index.php">← Kembali ke Dashboard</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-text">Article Management System</div>
        </footer>
    </div>
</body>

</html>