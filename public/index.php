<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$stmt = $conn->prepare("SELECT * FROM article WHERE status = 'published' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);

// Ambil 2 artikel untuk featured
$featuredArticles = array_splice($articles, 0, 2);
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
        font-size: 16px;
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

    /* Featured Articles Section */
    .featured-articles {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 48px;
        padding-bottom: 32px;
        border-bottom: 3px solid #E60026;
    }

    .featured-article {
        background-color: #FFFFFF;
        border-radius: 0;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(230, 0, 38, 0.12);
        border: 2px solid #E60026;
        padding-bottom: 24px;
        transition: all 0.3s ease;
    }

    .featured-article:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 32px rgba(230, 0, 38, 0.2);
    }

    .featured-article img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
        border-bottom: 3px solid #E60026;
    }

    .featured-content {
        padding: 20px 24px 0 24px;
    }

    .featured-title {
        font-family: 'Times New Roman', Times, serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.2;
        margin-bottom: 14px;
        text-decoration: none;
        transition: color 0.3s ease;
        letter-spacing: -0.01em;
    }

    .featured-title:hover {
        color: #E60026;
        text-decoration: none;
    }

    .featured-meta {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 0.75rem;
        color: #333333;
        margin-bottom: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .featured-meta span {
        display: flex;
        align-items: center;
        padding: 3px 8px;
        background-color: #F5F5F5;
        border: 1px solid #E60026;
        font-weight: 500;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #000000;
    }

    .featured-excerpt {
        font-size: 0.9rem;
        color: #333333;
        line-height: 1.6;
        margin-bottom: 0;
        text-align: justify;
        font-family: 'Times New Roman', Times, serif;
        font-style: italic;
    }

    /* More Articles Section */
    .more-articles-section {
        margin-top: 36px;
    }

    .article-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 32px;
    }

    .article-card {
        background-color: #FFFFFF;
        border-radius: 0;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border: 1px solid #E60026;
        padding-bottom: 20px;
        transition: all 0.3s ease;
    }

    .article-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(230, 0, 38, 0.2);
        border-color: #E60026;
    }

    .article-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
        border-bottom: 2px solid #E60026;
    }

    .article-card-content {
        padding: 16px 20px 0 20px;
    }

    .article-title {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.3;
        margin-bottom: 10px;
        text-decoration: none;
        display: block;
        transition: color 0.3s ease;
        letter-spacing: -0.01em;
    }

    .article-title:hover {
        color: #E60026;
        text-decoration: none;
    }

    .article-meta {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 0.7rem;
        color: #333333;
        margin-bottom: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .article-meta span {
        padding: 2px 6px;
        background-color: #F5F5F5;
        border: 1px solid #E60026;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.65rem;
    }

    .article-excerpt {
        font-size: 0.85rem;
        color: #333333;
        line-height: 1.6;
        font-family: 'Times New Roman', Times, serif;
        text-align: justify;
    }

    .no-articles {
        text-align: center;
        padding: 80px 24px;
        background-color: #F5F5F5;
        border-radius: 0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border: 2px solid #E60026;
    }

    .no-articles p {
        font-size: 1.125rem;
        color: #333333;
        font-weight: 400;
        font-family: 'Times New Roman', Times, serif;
    }

    /* Content section title styling */
    .content-section-title {
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #E60026;
        margin-bottom: 24px;
        padding-bottom: 8px;
        border-bottom: 2px solid #E60026;
        display: inline-block;
        letter-spacing: -0.01em;
        text-transform: uppercase;
    }

    /* Responsive Design */
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

        .featured-articles {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .article-grid {
            grid-template-columns: 1fr;
            gap: 24px;
            margin-top: 24px;
        }

        .featured-title {
            font-size: 1.3rem;
        }

        .featured-content {
            padding: 16px 20px 0 20px;
        }

        .featured-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
    }

    @media (max-width: 480px) {
        .featured-articles {
            margin-bottom: 32px;
        }

        .featured-article img {
            height: 200px;
        }

        .article-card img {
            height: 150px;
        }
    }
</style>

<div class="main-container">
    <div class="main-content">
        <?php if (empty($featuredArticles) && empty($otherArticles)): ?>
            <div class="no-articles">
                <p>Belum ada artikel yang tersedia.</p>
            </div>
        <?php else: ?>

            <?php if (!empty($featuredArticles)): ?>
                <div class="featured-articles">
                    <?php foreach ($featuredArticles as $featured): ?>
                        <article class="featured-article">
                            <?php if ($featured['picture']): ?>
                                <img src="../assets/images/<?= htmlspecialchars($featured['picture']) ?>" alt="<?= htmlspecialchars($featured['title']) ?>">
                            <?php endif; ?>

                            <div class="featured-content">
                                <h2><a href="artikel.php?id=<?= $featured['id'] ?>" class="featured-title">
                                        <?= htmlspecialchars($featured['title']) ?>
                                    </a></h2>

                                <div class="featured-meta">
                                    <span><?= date('F j, Y', strtotime($featured['created_at'])) ?></span>
                                    <?php
                                    $featuredAuthors = getAuthors($conn, $featured['id']);
                                    if (!empty($featuredAuthors)):
                                    ?>
                                        <span>By <?= implode(', ', $featuredAuthors) ?></span>
                                    <?php endif; ?>
                                    <?php
                                    $featuredCategories = getCategories($conn, $featured['id']);
                                    if (!empty($featuredCategories)):
                                    ?>
                                        <span><?= implode(', ', $featuredCategories) ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="featured-excerpt">
                                    <?= substr(strip_tags($featured['content']), 0, 150) ?>...
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($otherArticles)): ?>
                <div class="more-articles-section">
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
                                        <span><?= date('M j, Y', strtotime($article['created_at'])) ?></span>
                                        <?php
                                        $articleAuthors = getAuthors($conn, $article['id']);
                                        if (!empty($articleAuthors)):
                                        ?>
                                            <span>By <?= implode(', ', $articleAuthors) ?></span>
                                        <?php endif; ?>
                                        <?php
                                        $articleCategories = getCategories($conn, $article['id']);
                                        if (!empty($articleCategories)):
                                        ?>
                                            <span><?= implode(', ', $articleCategories) ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="article-excerpt">
                                        <?= substr(strip_tags($article['content']), 0, 120) ?>...
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <div class="sidebar-container">
        <?php require_once '../includes/sidebar.php'; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>