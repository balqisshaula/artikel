<?php
require_once '../auth.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = $_POST['title'];
    $date    = $_POST['date'];
    $content = $_POST['content'];
    $status  = $_POST['status'];
    $slug    = strtolower(str_replace(' ', '-', $title));
    $picture = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['picture']['tmp_name'], '../../assets/images/' . $filename);
        $picture = $filename;
    }


    $stmt = $conn->prepare("INSERT INTO article (title, slug, date, content, status, picture) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $slug, $date, $content, $status, $picture);
    $stmt->execute();

    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Artikel</title>
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
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        label {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333333;
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .required {
            color: #E60026;
            font-weight: 700;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        textarea,
        select {
            font-family: 'Times New Roman', 'Times', serif;
            width: 100%;
            padding: 14px;
            border: 1px solid #333333;
            background-color: #ffffff;
            color: #000000;
            font-size: 1rem;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #E60026;
            border-width: 2px;
            box-shadow: 0 0 8px rgba(230, 0, 38, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 180px;
            font-family: 'Times New Roman', 'Times', serif;
        }

        select[multiple] {
            height: 140px;
        }

        select option {
            padding: 8px;
            font-family: 'Times New Roman', 'Times', serif;
        }

        select option:checked {
            background-color: #E60026;
            color: #ffffff;
        }

        .submit-btn {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #E60026;
            color: #ffffff;
            padding: 16px 40px;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 2px;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background-color: #cc0022;
            box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
            transform: translateY(-1px);
        }

        .back-link {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #333333;
            text-align: center;
        }

        .back-link a {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #E60026;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            font-weight: 700;
            border-bottom: 2px solid transparent;
            transition: border-bottom-color 0.3s ease;
        }

        .back-link a:hover {
            border-bottom-color: #E60026;
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
            .container {
                padding: 0 15px;
            }

            h1 {
                font-size: 2rem;
            }

            .form-container {
                padding: 30px 25px;
            }

            .subtitle {
                font-size: 0.75rem;
                letter-spacing: 2px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 25px 20px;
            }

            h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-banner">ADMIN SYSTEM - CONTENT MANAGEMENT PORTAL</div>

    <header>
        <div class="container">
            <h1>Tambah Artikel</h1>
            <div class="subtitle">Create New Article</div>
        </div>
    </header>

    <div class="container">
        <?php
        $authors = $conn->query("SELECT * FROM author");
        $categories = $conn->query("SELECT * FROM category");
        ?>

        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-section">
                    <label>Judul <span class="required">*</span>:</label>
                    <input type="text" name="title" maxlength="255" required>
                </div>

                <div class="form-section">
                    <label>Upload Gambar:</label>
                    <input type="file" name="picture">
                </div>

                <div class="form-section">
                    <label>Tanggal <span class="required">*</span>:</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-section">
                    <label>Isi Artikel <span class="required">*</span>:</label>
                    <textarea name="content" rows="8" cols="60" required></textarea>
                </div>

                <div class="form-section">
                    <label>Pilih Penulis <span class="required">*</span>:</label>
                    <select name="author_ids[]" multiple required>
                        <?php while ($a = $authors->fetch_assoc()): ?>
                            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nickname']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-section">
                    <label>Pilih Kategori <span class="required">*</span>:</label>
                    <select name="category_ids[]" multiple required>
                        <?php while ($c = $categories->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-section">
                    <label>Status:</label>
                    <select name="status">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn">Simpan Artikel</button>
            </form>
        </div>

        <?php
        $article_id = $conn->insert_id;

        if (!empty($_POST['author_ids'])) {
            foreach ($_POST['author_ids'] as $author_id) {
                $stmt = $conn->prepare("INSERT INTO article_author (article_id, author_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $article_id, $author_id);
                $stmt->execute();
            }
        }

        if (!empty($_POST['category_ids'])) {
            foreach ($_POST['category_ids'] as $cat_id) {
                $stmt = $conn->prepare("INSERT INTO article_category (article_id, category_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $article_id, $cat_id);
                $stmt->execute();
            }
        }
        ?>

        <div class="back-link">
            <a href="list.php">← Kembali ke Daftar Artikel</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-text">Admin Dashboard System</div>
        </div>
    </footer>
</body>

</html>