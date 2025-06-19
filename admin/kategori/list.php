    <?php
    require_once '../auth.php';
    require_once '../../config/database.php';

    $result = $conn->query("SELECT * FROM category ORDER BY id ASC");
    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Kategori</title>
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
                max-width: 1200px;
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

            .action-section {
                margin-bottom: 40px;
                padding-bottom: 25px;
                border-bottom: 1px solid #333333;
            }

            .btn-add {
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
            }

            .btn-add:hover {
                background-color: #cc0022;
                color: #ffffff;
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
                background-color: #ffffff;
            }

            th {
                font-family: 'Helvetica', 'Arial', sans-serif;
                font-size: 0.9rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                background-color: #000000;
                color: #ffffff;
                padding: 18px 15px;
                text-align: left;
                border-bottom: 3px solid #E60026;
            }

            td {
                font-family: 'Times New Roman', 'Times', serif;
                font-size: 1rem;
                padding: 15px;
                border-bottom: 1px solid #ddd;
                vertical-align: top;
            }

            tr:nth-child(even) {
                background-color: #F5F5F5;
            }

            tr:hover {
                background-color: #f0f0f0;
                transition: background-color 0.2s ease;
            }

            .action-links {
                white-space: nowrap;
            }

            .action-links a {
                font-family: 'Helvetica', 'Arial', sans-serif;
                font-size: 0.85rem;
                color: #333333;
                text-decoration: none;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-weight: 700;
                margin-right: 15px;
                transition: all 0.3s ease;
            }

            .action-links a:hover {
                color: #E60026;
            }

            .action-links a.delete {
                color: #cc0022;
            }

            .action-links a.delete:hover {
                color: #E60026;
            }

            .back-section {
                margin-top: 40px;
                padding-top: 25px;
                border-top: 1px solid #333333;
                text-align: center;
            }

            .back-link {
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

            .back-link:hover {
                background-color: #E60026;
                color: #ffffff;
                box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
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

                .container {
                    padding: 0 15px;
                }

                table {
                    font-size: 14px;
                }

                th,
                td {
                    padding: 12px 8px;
                }

                .action-links a {
                    display: block;
                    margin-bottom: 5px;
                    margin-right: 0;
                }

                .btn-add,
                .back-link {
                    width: 100%;
                    text-align: center;
                }
            }

            @media (max-width: 480px) {
                h1 {
                    font-size: 1.8rem;
                }

                th,
                td {
                    padding: 10px 6px;
                    font-size: 0.9rem;
                }
            }
        </style>
    </head>

    <body>
        <div class="admin-banner">ADMIN SYSTEM - DATA KATEGORI</div>

        <header>
            <div class="container">
                <h1>Data Kategori</h1>
                <div class="subtitle">Content Management System</div>
            </div>
        </header>

        <div class="container">
            <div class="action-section">
                <a href="create.php" class="btn-add">+ Tambah Kategori</a>
            </div>

            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td class="action-links">
                                <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Yakin?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
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