<?php
$pageTitle = $pageTitle ?? "Tiara's Café";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Tiara's Café</title>

    <link rel="stylesheet" href="/tiaras_cafe/public/CSS/style.css">
</head>
<body>

<header class="site-header">
    <nav class="navbar">
        <a href="index.php" class="logo">Tiara's Café</a>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="reviews.php">Reviews</a></li>
            <li><a href="reservation.php">Reservations</a></li>
        </ul>
    </nav>
</header>

<main>
