<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$q = $_GET['q'] ?? '';
$safe = "%$q%";

$stmt = $conn->prepare("SELECT * FROM article WHERE status='published' AND title LIKE ? ORDER BY created_at DESC");
$stmt->bind_param("s", $safe);
$stmt->execute();
$result = $stmt->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);

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

<link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&family=Helvetica:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Georgia, "Times New Roman", serif;
        background-color: #FFFFFF;
        color: #000000;
        line-height: 1.6;
    }

    .main-container {
        display: flex;
        max-width: 1200px;
        margin: 0 auto;
        gap: 40px;
        padding: 32px 20px;
        border-top: 1px solid #000000;
    }

    .main-content {
        flex: 2;
    }

    .sidebar-container {
        flex: 1;
        min-width: 300px;
    }

    .search-header {
        background-color: #FFFFFF;
        padding: 32px 0;
        margin-bottom: 32px;
        border-bottom: 3px solid #E60026;
        position: relative;
    }

    .search-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background-color: #E60026;
    }

    .search-title {
        font-family: Georgia, "Times New Roman", serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.1;
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .search-query {
        color: #E60026;
        font-weight: 700;
        font-style: italic;
    }

    .search-info {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 0.875rem;
        color: #333333;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 8px;
    }

    .article-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 40px;
        margin-top: 40px;
    }

    .article-card {
        background-color: #FFFFFF;
        border: 1px solid #000000;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
    }

    .article-card:hover {
        box-shadow: 0 4px 16px rgba(230, 0, 38, 0.15);
        transform: translateY(-2px);
    }

    .article-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid #000000;
    }

    .article-card-content {
        padding: 20px;
    }

    .article-title {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 1.375rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.3;
        margin-bottom: 12px;
        text-decoration: none;
        display: block;
        transition: color 0.2s ease;
    }

    .article-title:hover {
        color: #E60026;
        text-decoration: none;
    }

    .article-meta {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 0.75rem;
        color: #333333;
        margin-bottom: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
        border-bottom: 1px solid #F5F5F5;
        padding-bottom: 8px;
    }

    .article-meta span {
        color: #333333;
    }

    .article-excerpt {
        font-family: Georgia, "Times New Roman", serif;
        font-size: 0.9375rem;
        color: #000000;
        line-height: 1.6;
    }

    .no-results {
        text-align: center;
        padding: 60px 24px;
        background-color: #F5F5F5;
        border: 1px solid #000000;
        margin-bottom: 32px;
    }

    .no-results-icon {
        font-size: 2rem;
        color: #E60026;
        margin-bottom: 16px;
    }

    .no-results-title {
        font-family: Georgia, "Times New Roman", serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 12px;
    }

    .no-results-text {
        font-family: Helvetica, Arial, sans-serif;
        color: #333333;
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .search-suggestions {
        background-color: #F5F5F5;
        border: 1px solid #000000;
        border-left: 4px solid #E60026;
        padding: 24px;
        margin-bottom: 32px;
    }

    .suggestions-title {
        font-family: Georgia, "Times New Roman", serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 16px;
    }

    .suggestions-list {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 0.9375rem;
        color: #333333;
        line-height: 1.6;
    }

    .search-navigation {
        padding: 24px 0;
        margin-top: 32px;
        border-top: 1px solid #000000;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        background-color: #E60026;
        color: #FFFFFF;
        text-decoration: none;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 12px 20px;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #E60026;
    }

    .back-link:hover {
        background-color: #FFFFFF;
        color: #E60026;
        text-decoration: none;
    }

    .back-link::before {
        content: "←";
        margin-right: 8px;
        font-size: 1rem;
    }

    .content-section-title {
        font-family: Georgia, "Times New Roman", serif;
        font-size: 1.875rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 24px;
        padding-bottom: 8px;
        border-bottom: 2px solid #E60026;
        display: inline-block;
        position: relative;
    }

    .content-section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background-color: #000000;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            gap: 24px;
            padding: 20px;
        }

        .search-title {
            font-size: 2rem;
        }

        .search-header {
            padding: 24px 0;
        }

        .article-grid {
            grid-template-columns: 1fr;
            gap: 32px;
            margin-top: 32px;
        }

        .search-suggestions {
            padding: 20px;
        }

        .content-section-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="main-container">
    <div class="main-content">
        <header class="search-header">
            <h1 class="search-title">
                Search Results: "<span class="search-query"><?= htmlspecialchars($q) ?></span>"
            </h1>
            <div class="search-info">
                <?= count($articles) ?> Articles Found
            </div>
        </header>

        <?php if (empty($articles)): ?>
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <h2 class="no-results-title">No Results Found</h2>
                <p class="no-results-text">
                    Sorry, no articles match your search for "<strong><?= htmlspecialchars($q) ?></strong>".
                </p>
            </div>

            <div class="search-suggestions">
                <h3 class="suggestions-title">Search Suggestions:</h3>
                <div class="suggestions-list">
                    • Check your spelling<br>
                    • Try more general keywords<br>
                    • Use synonyms or different words<br>
                    • Reduce the number of keywords
                </div>
            </div>
        <?php else: ?>
            <h2 class="content-section-title">Search Results</h2>
            <div class="article-grid">
                <?php foreach ($articles as $article): ?>
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
                                <?= substr(strip_tags($article['content']), 0, 150) ?>...
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <nav class="search-navigation">
            <a href="index.php" class="back-link">Back to Home</a>
        </nav>
    </div>

    <div class="sidebar-container">
        <?php require_once '../includes/sidebar.php'; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>