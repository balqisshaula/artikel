<?php
$kategoriQ = $conn->query("SELECT id, name FROM category ORDER BY name ASC");

$currentDir = dirname($_SERVER['SCRIPT_NAME']);
$basePath = '';

if (strpos($currentDir, '/public') !== false) {
    $basePath = './';
} else {
    $basePath = '../public/';
}

// Deteksi apakah sedang di halaman artikel
$isArticlePage = (basename($_SERVER['SCRIPT_NAME']) == 'artikel.php');
$currentArticleId = null;
$currentArticleCategories = [];

if ($isArticlePage && isset($_GET['id'])) {
    $currentArticleId = (int)$_GET['id'];

    $catQuery = $conn->query("SELECT c.id, c.name FROM article_category ac JOIN category c ON ac.category_id = c.id WHERE ac.article_id = $currentArticleId");
    while ($cat = $catQuery->fetch_assoc()) {
        $currentArticleCategories[] = $cat['id'];
    }
}
?>

<div class="sidebar">
    <!-- Search Section -->
    <div class="sidebar-section">
        <h3>Search Articles</h3>
        <form method="GET" action="<?= $basePath ?>search.php">
            <input type="text" name="q" placeholder="Search by title..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php if ($isArticlePage && !empty($currentArticleCategories)): ?>
        <!-- Related Articles Section -->
        <div class="sidebar-section">
            <h3>Related Stories</h3>
            <div class="related-articles">
                <?php
                $categoryIds = implode(',', $currentArticleCategories);
                $relatedQuery = $conn->query("
                    SELECT DISTINCT a.id, a.title, a.picture, a.created_at 
                    FROM article a 
                    JOIN article_category ac ON a.id = ac.article_id 
                    WHERE ac.category_id IN ($categoryIds) 
                    AND a.id != $currentArticleId 
                    AND a.status = 'published' 
                    ORDER BY a.created_at DESC 
                    LIMIT 5
                ");

                if ($relatedQuery && $relatedQuery->num_rows > 0):
                ?>
                    <?php while ($related = $relatedQuery->fetch_assoc()): ?>
                        <div class="related-article-item">
                            <?php if ($related['picture']): ?>
                                <div class="related-article-image">
                                    <img src="../assets/images/<?= htmlspecialchars($related['picture']) ?>"
                                        alt="<?= htmlspecialchars($related['title']) ?>">
                                </div>
                            <?php endif; ?>
                            <div class="related-article-content">
                                <h4><a href="artikel.php?id=<?= $related['id'] ?>">
                                        <?= htmlspecialchars($related['title']) ?>
                                    </a></h4>
                                <div class="article-meta">
                                    <?= date('M j, Y', strtotime($related['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-content">
                        <p>No related articles found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Categories Section -->
        <div class="sidebar-section">
            <h3>Categories</h3>
            <ul class="category-list">
                <?php if ($kategoriQ && $kategoriQ->num_rows > 0): ?>
                    <?php while ($k = $kategoriQ->fetch_assoc()): ?>
                        <li><a href="<?= $basePath ?>kategori.php?id=<?= $k['id'] ?>">
                                <?= htmlspecialchars($k['name']) ?>
                            </a></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="no-categories">No categories available</li>
                <?php endif; ?>
            </ul>
        </div>


        <!-- About Section -->
        <div class="sidebar-section about-section">
            <h3>About BeritaKini</h3>
            <div class="about-content">
                <p>BeritaKini adalah situs berita digital yang hadir untuk memenuhi kebutuhan informasi masyarakat Indonesia. Dengan liputan mendalam dan gaya penulisan yang informatif, BeritaKini menyajikan topik-topik penting seputar ekonomi & bisnis, kesehatan, hingga sosial & budaya. Kami mengedepankan keakuratan dan kecepatan dalam menyampaikan berita agar pembaca selalu mendapatkan informasi yang relevan.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Sidebar Styles - Time Magazine Theme */
    .sidebar {
        background-color: #ffffff;
        border: 2px solid #333333;
        border-radius: 0;
        padding: 0;
        margin-bottom: 40px;
        height: fit-content;
        position: sticky;
        top: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .sidebar-section {
        padding: 25px;
        border-bottom: 1px solid #F5F5F5;
    }

    .sidebar-section:last-child {
        border-bottom: none;
    }

    .sidebar h3 {
        color: #000000;
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 3px solid #E60026;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    /* Search Form */
    .sidebar form {
        margin-bottom: 0;
    }

    .sidebar input[type="text"],
    .sidebar input[type="email"] {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #333333;
        border-radius: 0;
        margin-bottom: 15px;
        font-size: 0.9rem;
        font-family: 'Georgia', 'Times New Roman', serif;
        background-color: #ffffff;
        transition: all 0.3s ease;
        color: #000000;
    }

    .sidebar input[type="text"]:focus,
    .sidebar input[type="email"]:focus {
        outline: none;
        border-color: #E60026;
        box-shadow: 0 0 0 1px #E60026;
        background-color: #ffffff;
    }

    .sidebar input[type="text"]::placeholder,
    .sidebar input[type="email"]::placeholder {
        color: #333333;
        font-style: italic;
    }

    .sidebar button {
        width: 100%;
        padding: 12px 20px;
        background-color: #E60026;
        color: #ffffff;
        border: 2px solid #E60026;
        border-radius: 0;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Helvetica', Arial, sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .sidebar button:hover {
        background-color: #ffffff;
        color: #E60026;
        border-color: #E60026;
        box-shadow: 0 2px 6px rgba(230, 0, 38, 0.2);
    }

    /* Category List */
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-list li {
        margin-bottom: 0;
        border-bottom: 1px solid #F5F5F5;
    }

    .category-list li:last-child {
        border-bottom: none;
    }

    .category-list li a {
        display: block;
        padding: 12px 0;
        color: #000000;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        font-family: 'Georgia', 'Times New Roman', serif;
        border-left: 3px solid transparent;
        padding-left: 15px;
        margin-left: -15px;
    }

    .category-list li a:hover {
        color: #E60026;
        text-decoration: none;
        border-left-color: #E60026;
        background-color: #F5F5F5;
        font-weight: 600;
    }

    .no-categories {
        color: #333333;
        font-style: italic;
        padding: 12px 0;
    }

    /* Related Articles */
    .related-articles {
        margin: 0;
    }

    .related-article-item {
        display: flex;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #F5F5F5;
        transition: all 0.3s ease;
    }

    .related-article-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .related-article-item:hover {
        background-color: #F5F5F5;
        border-radius: 0;
        padding: 12px;
        margin: 0 -12px 20px -12px;
        border-left: 4px solid #E60026;
    }

    .related-article-image {
        flex: 0 0 80px;
        margin-right: 12px;
    }

    .related-article-image img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 0;
        transition: none;
        border: 2px solid #333333;
    }

    .related-article-content {
        flex: 1;
    }

    .related-article-content h4 {
        margin: 0 0 6px 0;
        font-size: 0.85rem;
        line-height: 1.3;
        font-weight: 600;
        font-family: 'Helvetica', Arial, sans-serif;
    }

    .related-article-content h4 a {
        color: #000000;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }

    .related-article-content h4 a:hover {
        color: #E60026;
        text-decoration: none;
    }

    .article-meta {
        font-size: 0.75rem;
        color: #333333;
        font-family: 'Helvetica', Arial, sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    /* Newsletter Section */
    .newsletter-section {
        background-color: #F5F5F5;
    }

    .newsletter-content p {
        color: #333333;
        line-height: 1.6;
        margin-bottom: 15px;
        font-size: 0.9rem;
        font-family: 'Georgia', 'Times New Roman', serif;
    }

    .newsletter-form {
        margin: 0;
    }

    /* About Section */
    .about-content p {
        color: #333333;
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 0.9rem;
        font-family: 'Georgia', 'Times New Roman', serif;
        font-style: italic;
    }

    .about-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-number {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #E60026;
        font-family: 'Helvetica', Arial, sans-serif;
    }

    .stat-label {
        display: block;
        font-size: 0.75rem;
        color: #333333;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-family: 'Helvetica', Arial, sans-serif;
        font-weight: 500;
    }

    /* Trending Topics */
    .trending-section {
        background-color: #000000;
        color: #ffffff;
    }

    .trending-section h3 {
        color: #ffffff;
        border-bottom-color: #E60026;
    }

    .trending-topics {
        margin: 0;
    }

    .trending-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #333333;
        transition: all 0.3s ease;
    }

    .trending-item:last-child {
        border-bottom: none;
    }

    .trending-item:hover {
        background-color: #333333;
        margin: 0 -25px;
        padding: 10px 25px;
        border-left: 4px solid #E60026;
    }

    .trending-number {
        flex: 0 0 30px;
        font-size: 1.2rem;
        font-weight: 700;
        color: #E60026;
        font-family: 'Helvetica', Arial, sans-serif;
    }

    .trending-title {
        flex: 1;
        font-size: 0.9rem;
        font-weight: 500;
        color: #ffffff;
        font-family: 'Georgia', 'Times New Roman', serif;
    }

    .no-content p {
        color: #333333;
        font-style: italic;
        margin: 0;
        padding: 20px 0;
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar-section {
            padding: 20px;
        }

        .sidebar h3 {
            font-size: 1rem;
        }

        .about-stats {
            flex-direction: column;
            gap: 10px;
        }

        .trending-item:hover {
            margin: 0;
            padding: 10px 0;
        }
    }
</style>