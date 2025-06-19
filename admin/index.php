<?php
require_once 'auth.php';
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
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

        .dashboard-nav {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }

        .nav-card {
            border: 1px solid #333333;
            background-color: #ffffff;
            padding: 35px 30px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .nav-card:hover {
            background-color: #F5F5F5;
            border-color: #E60026;
            box-shadow: 0 4px 16px rgba(230, 0, 38, 0.15);
            transform: translateY(-2px);
        }

        .nav-card a {
            text-decoration: none;
            color: #000000;
            display: block;
        }

        .nav-card h3 {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 18px;
            border-bottom: 2px solid #E60026;
            padding-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .nav-card p {
            font-family: 'Times New Roman', 'Times', serif;
            color: #333333;
            font-size: 1rem;
            line-height: 1.6;
            font-weight: 400;
        }

        .nav-card:hover h3 {
            color: #E60026;
        }

        .nav-card:hover p {
            color: #000000;
        }

        .logout-section {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid #333333;
            text-align: center;
        }

        .logout-btn {
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

        .logout-btn:hover {
            background-color: #cc0022;
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

        body {
            padding-top: 35px;
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

            .dashboard-nav {
                grid-template-columns: 1fr;
                gap: 25px;
                margin: 40px 0;
            }

            .nav-card {
                padding: 30px 25px;
            }

            .nav-card h3 {
                font-size: 1.2rem;
            }

            .nav-card p {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .nav-card {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-banner">ADMIN SYSTEM - CONTENT MANAGEMENT PORTAL</div>

    <header>
        <div class="container">
            <h1>Dashboard Admin</h1>
            <div class="subtitle">System Management Portal</div>
        </div>
    </header>

    <div class="container">
        <div class="dashboard-nav">
            <div class="nav-card">
                <a href="artikel/list.php">
                    <h3>Kelola Artikel</h3>
                    <p>Manage and organize all articles, create new content, edit existing posts, and maintain your publication's editorial standards.</p>
                </a>
            </div>

            <div class="nav-card">
                <a href="kategori/list.php">
                    <h3>Kelola Kategori</h3>
                    <p>Organize content categories, create new sections, and maintain the taxonomical structure of your publication.</p>
                </a>
            </div>

            <div class="nav-card">
                <a href="penulis/list.php">
                    <h3>Kelola Penulis</h3>
                    <p>Manage writer profiles, author information, and contributor access to maintain editorial quality and accountability.</p>
                </a>
            </div>
        </div>

        <div class="logout-section">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-text">Admin Dashboard System</div>
        </div>
    </footer>
</body>

</html>