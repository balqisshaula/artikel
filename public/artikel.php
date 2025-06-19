<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM article WHERE id = ? AND status = 'published'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) die("Artikel tidak ditemukan");

$authors = [];
$auth = $conn->query("SELECT a.nickname FROM article_author aa JOIN author a ON aa.author_id = a.id WHERE aa.article_id = $id");
while ($a = $auth->fetch_assoc()) $authors[] = $a['nickname'];

$categories = [];
$cat = $conn->query("SELECT c.name FROM article_category ac JOIN category c ON ac.category_id = c.id WHERE ac.article_id = $id");
while ($c = $cat->fetch_assoc()) $categories[] = $c['name'];
?>

<link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&family=Helvetica:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        background-color: #FFFFFF;
        color: #000000;
        line-height: 1.6;
        font-size: 18px;
    }

    .article-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #FFFFFF;
        padding: 0;
        border-radius: 0;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #E60026;
    }

    .article-header {
        margin-bottom: 40px;
        padding: 40px 40px 0;
        border-bottom: 3px solid #E60026;
        padding-bottom: 30px;
        background-color: #FFFFFF;
    }

    .article-title {
        font-family: 'Times New Roman', Times, serif;
        font-size: 2.75rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.1;
        margin-bottom: 24px;
        word-wrap: break-word;
        letter-spacing: -0.02em;
    }

    .article-meta {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 0.8125rem;
        color: #333333;
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        text-transform: uppercase;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        font-weight: 500;
        padding: 4px 8px;
        background-color: #F5F5F5;
        border: 1px solid #E60026;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-item strong {
        color: #E60026;
        margin-right: 4px;
        font-weight: 600;
    }

    .article-image {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        margin-bottom: 20px;
        border-radius: 0;
        border-top: 2px solid #E60026;
        border-bottom: 2px solid #E60026;
        box-shadow: 0 4px 12px rgba(230, 0, 38, 0.2);
    }

    .article-content {
        font-size: 1.125rem;
        line-height: 1.7;
        color: #000000;
        margin-bottom: 40px;
        padding: 0 40px;
        font-family: 'Times New Roman', Times, serif;
    }

    .article-content p {
        margin-bottom: 22px;
        text-align: justify;
    }

    .article-content h2 {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #E60026;
        margin: 36px 0 18px 0;
        letter-spacing: -0.02em;
        line-height: 1.3;
        text-transform: uppercase;
    }

    .article-content h3 {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #000000;
        margin: 28px 0 14px 0;
        letter-spacing: -0.01em;
        line-height: 1.3;
    }

    .article-content ul,
    .article-content ol {
        margin: 18px 0;
        padding-left: 28px;
    }

    .article-content li {
        margin-bottom: 8px;
    }

    .article-content blockquote {
        border-left: 4px solid #E60026;
        padding-left: 28px;
        margin: 28px 0;
        font-style: italic;
        color: #333333;
        background-color: #F5F5F5;
        padding: 28px 0 28px 28px;
        margin-right: 0;
        font-size: 1.1875rem;
        line-height: 1.6;
        position: relative;
    }

    .article-content blockquote::before {
        content: '"';
        font-size: 4rem;
        color: #E60026;
        position: absolute;
        left: -10px;
        top: -15px;
        font-family: 'Times New Roman', serif;
    }

    .article-navigation {
        padding: 32px 40px;
        border-top: 3px solid #E60026;
        margin-top: 40px;
        background-color: #F5F5F5;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        color: #FFFFFF;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        font-family: 'Helvetica', Arial, sans-serif;
        padding: 12px 24px;
        background-color: #E60026;
        border: 2px solid #E60026;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .back-link:hover {
        background-color: #FFFFFF;
        color: #E60026;
        border-color: #E60026;
        transform: translateY(-2px);
    }

    .back-link::before {
        content: "←";
        margin-right: 8px;
        font-size: 1rem;
    }

    .main-container {
        display: flex;
        max-width: 1200px;
        margin: 0 auto;
        gap: 40px;
        padding: 32px 20px;
        background-color: #FFFFFF;
    }

    .main-content {
        flex: 2;
    }

    .sidebar-container {
        flex: 1;
        min-width: 300px;
        border-left: 2px solid #E60026;
        padding-left: 40px;
    }

    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            gap: 24px;
            padding: 20px;
        }

        .sidebar-container {
            border-left: none;
            border-top: 2px solid #E60026;
            padding-left: 0;
            padding-top: 24px;
        }

        .article-title {
            font-size: 2.25rem;
        }

        .article-header,
        .article-content,
        .article-navigation {
            padding-left: 24px;
            padding-right: 24px;
        }

        .article-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<div class="main-container">
    <div class="main-content">
        <article class="article-container">
            <header class="article-header">
                <h1 class="article-title"><?= htmlspecialchars($data['title']) ?></h1>

                <div class="article-meta">
                    <span class="meta-item">
                        <strong>Published:</strong> <?= date('F j, Y', strtotime($data['created_at'])) ?>
                    </span>
                    <?php if (!empty($authors)): ?>
                        <span class="meta-item">
                            <strong>By</strong> <?= implode(', ', $authors) ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($categories)): ?>
                        <span class="meta-item">
                            <strong>Category:</strong> <?= implode(', ', $categories) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <?php if ($data['picture']): ?>
                <div style="padding: 0 40px;">
                    <img src="../assets/images/<?= htmlspecialchars($data['picture']) ?>"
                        alt="<?= htmlspecialchars($data['title']) ?>"
                        class="article-image">
                </div>
            <?php endif; ?>

            <div class="article-content">
                <?= $data['content'] ?>
            </div>

            <nav class="article-navigation">
                <a href="index.php" class="back-link">Back to Home</a>
            </nav>
        </article>
    </div>

    <div class="sidebar-container">
        <?php require_once '../includes/sidebar.php'; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>