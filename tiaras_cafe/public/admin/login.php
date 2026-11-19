<?php

session_start();

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Database\Database;
use App\Controllers\AdminController;
use App\Repositories\AdminRepository;

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === "" || $password === "") {
        $error = "Please enter your username and password.";
    } else {

        $database = new Database();
        $pdo = $database->connect();

        if ($pdo !== null) {

            $repository = new AdminRepository($pdo);
            $controller = new AdminController();

            if ($controller->login($username, $password, $repository)) {
                $_SESSION['username'] = $_SESSION['admin_username'];

                header("Location: dashboard.php");
                exit;
            }

            $error = "Invalid username or password.";

        } else {
            $error = "Unable to connect to the database. Please try again later.";
        }
    }
}

$pageTitle = "Admin Login";

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

    <main class="admin-login">

        <h1>Tiara's Café</h1>

        <h2>Administrator Login</h2>

        <p>Log in to access the admin dashboard.</p>

        <?php if ($error !== ""): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div>
                <label for="username">Username or Email:</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    autocomplete="username"
                    required
                >
            </div>

            <div>
                <label for="password">Password:</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit">Log In</button>

        </form>

        <a href="../index.php">Back to Website</a>

    </main>

</body>

</html>