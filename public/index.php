<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$stmt = $conn->prepare("SELECT * FROM article WHERE status = 'published' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);

$headline = array_shift($articles);
$otherArticles = $articles;

function getAuthors($conn, $articleId)
{
    $authors = [];
    $auth = $conn->query("SELECT a.nickname FROM article_author aa JOIN author a ON aa.author_id = a.id WHERE aa.article_id = $articleId");
    while ($a = $auth->fetch_assoc()) $authors[] = $a['nickname'];
    return $authors;
}

function getCategories($conn, $articleId)
{
    $categories = [];
    $cat = $conn->query("SELECT c.name FROM article_category ac JOIN category c ON ac.category_id = c.id WHERE ac.article_id = $articleId");
    while ($c = $cat->fetch_assoc()) $categories[] = $c['name'];
    return $categories;
}
?>

<link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&family=Helvetica:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        color: #000000;
        line-height: 1.6;
        font-size: 16px;
        min-height: 100vh;
    }

    .main-container {
        display: flex;
        max-width: 1400px;
        margin: 0 auto;
        gap: 50px;
        padding: 40px 20px;
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        min-height: 100vh;
    }

    .main-content {
        flex: 2;
        background: #ffffff;
        border: 3px solid #E60026;
        border-radius: 0;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(230, 0, 38, 0.15);
        position: relative;
    }

    .main-content::before {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        height: 6px;
        background: linear-gradient(90deg, #E60026 0%, #cc0022 50%, #E60026 100%);
    }

    .sidebar-container {
        flex: 1;
        min-width: 320px;
        background: #ffffff;
        border: 3px solid #E60026;
        border-radius: 0;
        padding: 32px;
        box-shadow: 0 10px 40px rgba(230, 0, 38, 0.15);
        height: fit-content;
        position: sticky;
        top: 40px;
        position: relative;
    }

    .sidebar-container::before {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        height: 6px;
        background: linear-gradient(90deg, #E60026 0%, #cc0022 50%, #E60026 100%);
    }

    .headline-article {
        background: #ffffff;
        border: 2px solid #333333;
        margin-bottom: 60px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 0;
        transition: all 0.3s ease;
        position: relative;
    }

    .headline-article::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        height: 4px;
        background: linear-gradient(90deg, #E60026 0%, #cc0022 100%);
        z-index: 1;
    }

    .headline-article:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(230, 0, 38, 0.2);
        border-color: #E60026;
    }

    .headline-article img {
        width: 100%;
        height: 450px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
        border-bottom: 2px solid #333333;
    }

    .headline-article:hover img {
        transform: scale(1.02);
    }

    .headline-content {
        padding: 40px;
        background: #ffffff;
    }

    .headline-title {
        font-family: 'Times New Roman', Times, serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.1;
        margin-bottom: 20px;
        text-decoration: none;
        transition: color 0.3s ease;
        letter-spacing: -0.02em;
    }

    .headline-title:hover {
        color: #E60026;
        text-decoration: none;
    }

    .headline-title:focus {
        outline: 2px solid #E60026;
        outline-offset: 2px;
    }

    .headline-meta {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 0.9rem;
        color: #333333;
        margin-bottom: 24px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
    }

    .headline-meta span {
        display: flex;
        align-items: center;
        padding: 10px 18px;
        background: #ffffff;
        border: 2px solid #333333;
        color: #000000;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .headline-meta span:hover {
        background: #E60026;
        color: #ffffff;
        border-color: #E60026;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
    }

    .headline-excerpt {
        font-size: 1.1rem;
        color: #333333;
        line-height: 1.7;
        margin-bottom: 0;
        text-align: justify;
        font-family: 'Times New Roman', Times, serif;
        font-weight: 400;
    }

    .article-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
        margin-top: 60px;
    }

    .article-card {
        background: #ffffff;
        border: 2px solid #333333;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 0;
        transition: all 0.3s ease;
        position: relative;
    }

    .article-card::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        height: 4px;
        background: linear-gradient(90deg, #E60026 0%, #cc0022 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
        transform-origin: left;
        z-index: 1;
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(230, 0, 38, 0.2);
        border-color: #E60026;
    }

    .article-card:hover::before {
        transform: scaleX(1);
    }

    .article-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
        border-bottom: 2px solid #333333;
    }

    .article-card:hover img {
        transform: scale(1.03);
    }

    .article-card-content {
        padding: 32px;
        background: #ffffff;
    }

    .article-title {
        font-family: 'Times New Roman', Times, serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.3;
        margin-bottom: 16px;
        text-decoration: none;
        display: block;
        transition: color 0.3s ease;
        letter-spacing: -0.01em;
    }

    .article-title:hover {
        color: #E60026;
        text-decoration: none;
    }

    .article-title:focus {
        outline: 2px solid #E60026;
        outline-offset: 2px;
    }

    .article-meta {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 0.85rem;
        color: #333333;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .article-meta span {
        padding: 8px 16px;
        background: #ffffff;
        border: 2px solid #333333;
        color: #000000;
        font-weight: 600;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .article-meta span:hover {
        background: #E60026;
        color: #ffffff;
        border-color: #E60026;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(230, 0, 38, 0.3);
    }

    .article-excerpt {
        font-size: 0.95rem;
        color: #333333;
        line-height: 1.6;
        font-family: 'Times New Roman', Times, serif;
        text-align: justify;
        font-weight: 400;
    }

    .no-articles {
        text-align: center;
        padding: 100px 32px;
        background: #ffffff;
        border: 2px solid #333333;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .no-articles::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        height: 4px;
        background: linear-gradient(90deg, #E60026 0%, #cc0022 100%);
    }

    .no-articles p {
        font-size: 1.2rem;
        color: #333333;
        font-weight: 400;
        font-family: 'Times New Roman', Times, serif;
        letter-spacing: 0.02em;
    }

    .no-articles::after {
        content: "📝";
        font-size: 3.5rem;
        display: block;
        margin-bottom: 20px;
        opacity: 0.4;
    }

    .content-section-title {
        font-family: 'Times New Roman', Times, serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 40px;
        padding-bottom: 16px;
        border-bottom: 3px solid #E60026;
        display: inline-block;
        letter-spacing: -0.02em;
        position: relative;
        text-transform: uppercase;
    }

    .content-section-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 40%;
        height: 3px;
        background: linear-gradient(90deg, #E60026 0%, #cc0022 100%);
    }

    /* Focus and accessibility improvements */
    *:focus {
        outline: 2px solid #E60026;
        outline-offset: 2px;
    }

    .article-card,
    .headline-article {
        cursor: pointer;
    }

    .article-card:focus-within,
    .headline-article:focus-within {
        outline: 2px solid rgba(230, 0, 38, 0.5);
        outline-offset: 2px;
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Selection styling */
    ::selection {
        background-color: #E60026;
        color: #ffffff;
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f5f5f5;
    }

    ::-webkit-scrollbar-thumb {
        background: #E60026;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #cc0022;
    }

    /* Loading animation placeholder */
    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    .loading-placeholder {
        background: linear-gradient(90deg, #f5f5f5 0%, #E60026 50%, #f5f5f5 100%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            gap: 30px;
            padding: 20px;
        }

        .sidebar-container {
            position: static;
            margin-top: 30px;
        }

        .main-content {
            padding: 30px;
        }

        .headline-title {
            font-size: 2rem;
        }

        .headline-content {
            padding: 30px;
        }

        .article-grid {
            grid-template-columns: 1fr;
            gap: 30px;
            margin-top: 40px;
        }

        .headline-meta,
        .article-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .headline-meta span,
        .article-meta span {
            width: 100%;
            justify-content: center;
        }

        .content-section-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 480px) {
        .main-container {
            padding: 15px;
            gap: 20px;
        }

        .main-content,
        .sidebar-container {
            padding: 20px;
        }

        .headline-title {
            font-size: 1.8rem;
        }

        .headline-content {
            padding: 20px;
        }

        .article-card-content {
            padding: 20px;
        }

        .content-section-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="main-container">
    <div class="main-content">
        <?php if (empty($articles) && !$headline): ?>
            <div class="no-articles">
                <p>Belum ada artikel yang tersedia.</p>
            </div>
        <?php else: ?>

            <?php if ($headline): ?>
                <article class="headline-article">
                    <?php if ($headline['picture']): ?>
                        <img src="../assets/images/<?= htmlspecialchars($headline['picture']) ?>" alt="<?= htmlspecialchars($headline['title']) ?>">
                    <?php endif; ?>

                    <div class="headline-content">
                        <h1><a href="artikel.php?id=<?= $headline['id'] ?>" class="headline-title">
                                <?= htmlspecialchars($headline['title']) ?>
                            </a></h1>

                        <div class="headline-meta">
                            <span>📅 <?= date('F j, Y', strtotime($headline['created_at'])) ?></span>
                            <?php
                            $headlineAuthors = getAuthors($conn, $headline['id']);
                            if (!empty($headlineAuthors)):
                            ?>
                                <span>✍️ <?= implode(', ', $headlineAuthors) ?></span>
                            <?php endif; ?>
                            <?php
                            $headlineCategories = getCategories($conn, $headline['id']);
                            if (!empty($headlineCategories)):
                            ?>
                                <span>🏷️ <?= implode(', ', $headlineCategories) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="headline-excerpt">
                            <?= substr(strip_tags($headline['content']), 0, 200) ?>...
                        </div>
                    </div>
                </article>
            <?php endif; ?>

            <?php if (!empty($otherArticles)): ?>
                <h2 class="content-section-title">More Articles</h2>
                <div class="article-grid">
                    <?php foreach ($otherArticles as $article): ?>
                        <article class="article-card">
                            <?php if ($article['picture']): ?>
                                <img src="../assets/images/<?= htmlspecialchars($article['picture']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                            <?php endif; ?>

                            <div class="article-card-content">
                                <h3><a href="artikel.php?id=<?= $article['id'] ?>" class="article-title">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a></h3>

                                <div class="article-meta">
                                    <span>📅 <?= date('M j, Y', strtotime($article['created_at'])) ?></span>
                                    <?php
                                    $articleAuthors = getAuthors($conn, $article['id']);
                                    if (!empty($articleAuthors)):
                                    ?>
                                        <span>✍️ <?= implode(', ', $articleAuthors) ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="article-excerpt">
                                    <?= substr(strip_tags($article['content']), 0, 150) ?>...
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <div class="sidebar-container">
        <?php require_once '../includes/sidebar.php'; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>