<?php
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeritaKini</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georgia:wght@400;700&family=Helvetica:wght@400;500;700&family=Times+New+Roman:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #ffffff;
            color: #000000;
            line-height: 1.6;
        }

        .breaking-news {
            background-color: #E60026;
            color: #ffffff;
            padding: 8px 0;
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .main-header {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 3rem;
            font-weight: 700;
            color: #E60026 !important;
            text-decoration: none;
            letter-spacing: -0.02em;
            padding: 20px 0;
            transition: none;
            text-transform: uppercase;
        }

        .navbar-brand:hover {
            color: #E60026 !important;
            text-decoration: none;
        }

        .navbar {
            padding: 0;
            border-bottom: 1px solid #333333;
        }

        .navbar-nav .nav-link {
            color: #000000 !important;
            font-family: 'Helvetica', Arial, sans-serif;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 15px 20px !important;
            margin: 0;
            letter-spacing: 0.3px;
            border: none;
            border-radius: 0;
            transition: all 0.3s ease;
            position: relative;
            text-transform: uppercase;
        }

        .navbar-nav .nav-link:hover {
            color: #E60026 !important;
            background-color: #F5F5F5;
        }

        .navbar-nav .nav-link.active {
            color: #E60026 !important;
            background-color: #F5F5F5;
            font-weight: 700;
        }

        .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 3px;
            background-color: #E60026;
        }

        .sidebar {
            background-color: #ffffff;
            border: 1px solid #333333;
            border-radius: 0;
            padding: 25px;
            margin-bottom: 30px;
            height: fit-content;
            position: sticky;
            top: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            color: #000000;
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E60026;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar form {
            margin-bottom: 30px;
        }

        .sidebar input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #333333;
            border-radius: 0;
            margin-bottom: 15px;
            font-size: 0.9rem;
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #ffffff;
            transition: border-color 0.3s ease;
            color: #000000;
        }

        .sidebar input[type="text"]:focus {
            outline: none;
            border-color: #E60026;
            box-shadow: 0 0 0 1px #E60026;
        }

        .sidebar input[type="text"]::placeholder {
            color: #333333;
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
            letter-spacing: 0.5px;
        }

        .sidebar button:hover {
            background-color: #ffffff;
            color: #E60026;
            border-color: #E60026;
            transform: none;
            box-shadow: 0 2px 4px rgba(230, 0, 38, 0.2);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .sidebar li {
            margin-bottom: 0;
            border-bottom: 1px solid #F5F5F5;
        }

        .sidebar li:last-child {
            border-bottom: none;
        }

        .sidebar li a {
            display: block;
            padding: 12px 0;
            color: #000000;
            text-decoration: none;
            font-weight: 400;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-family: 'Georgia', 'Times New Roman', serif;
        }

        .sidebar li a:hover {
            color: #E60026;
            text-decoration: none;
            padding-left: 10px;
            font-weight: 500;
        }

        .sidebar p {
            color: #333333;
            line-height: 1.7;
            margin-bottom: 0;
            font-size: 0.9rem;
            padding: 20px;
            background-color: #F5F5F5;
            border-left: 4px solid #E60026;
            border-radius: 0;
            font-style: italic;
        }

        .main-container {
            display: flex;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .main-content {
            flex: 2;
        }

        .sidebar-container {
            flex: 1;
            max-width: 320px;
        }

        .main-footer {
            background-color: #000000;
            border-top: 3px solid #E60026;
            margin-top: 60px;
            padding: 40px 0;
        }

        .footer-content {
            text-align: center;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 400;
            font-family: 'Helvetica', Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-content p {
            margin: 0;
            padding: 20px 0;
            border: none;
            border-radius: 0;
            background-color: transparent;
        }

        .content-area {
            min-height: 60vh;
            padding: 60px 0;
        }

        .demo-card {
            background: #ffffff;
            border: 2px solid #333333;
            border-radius: 0;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .demo-card h2 {
            color: #000000;
            margin-bottom: 20px;
            font-weight: 700;
            font-family: 'Helvetica', Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .demo-card p {
            color: #333333;
            line-height: 1.7;
            font-family: 'Georgia', 'Times New Roman', serif;
        }

        .related-articles {
            margin-top: 20px;
        }

        .related-article-item {
            display: flex;
            margin-bottom: 25px;
            padding-bottom: 20px;
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
            padding: 15px;
            margin: 0 -15px 25px -15px;
            border-left: 3px solid #E60026;
        }

        .related-article-image {
            flex: 0 0 90px;
            margin-right: 15px;
        }

        .related-article-image img {
            width: 90px;
            height: 70px;
            object-fit: cover;
            border-radius: 0;
            transition: none;
            border: 2px solid #333333;
        }

        .related-article-content {
            flex: 1;
        }

        .related-article-content h4 {
            margin: 0 0 8px 0;
            font-size: 0.9rem;
            line-height: 1.4;
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
    </style>
</head>

<body>
    <div class="breaking-news">
        Breaking: Latest News Updates - Stay Informed
    </div>

    <header class="main-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid px-0">
                    <a class="navbar-brand" href="/">BeritaKini</a>

                    <div class="navbar-nav ms-auto">
                        <a class="nav-link active" href="/">Beranda</a>
                        <a class="nav-link" href="/tentang">Tentang</a>
                        <a class="nav-link" href="/kontak">Kontak</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>