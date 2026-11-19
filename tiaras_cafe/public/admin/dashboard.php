<?php

require_once dirname(__DIR__, 2) . '/includes/admin_auth.php';

$pageTitle = "Admin Dashboard";

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

    <header>
        <h1>Tiara's Café</h1>
        <h2>Admin Dashboard</h2>
    </header>

    <main class="admin-dashboard">

        <section>
            <h2>Welcome, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Administrator') ?>!</h2>
            <p>Manage the café's website information and customer requests.</p>
        </section>

        <section class="admin-menu">
            <h2>Menu Management</h2>
            <p>Add, edit, view, and delete menu items.</p>
            <a href="menu-management.php">Manage Menu</a>
        </section>

        <section class="admin-reviews">
            <h2>Review Management</h2>
            <p>View and manage customer ratings and reviews.</p>
            <a href="review-management.php">Manage Reviews</a>
        </section>

        <section class="admin-reservations">
            <h2>Reservation Management</h2>
            <p>View, confirm, or reject customer reservation requests.</p>
            <a href="reservation-management.php">Manage Reservations</a>
        </section>

        <section>
            <a href="logout.php">Log Out</a>
        </section>

    </main>

</body>

</html>