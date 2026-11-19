<?php

require_once dirname(__DIR__, 2) . '/includes/admin_auth.php';

use App\Database\Database;
use App\Repositories\ReviewRepository;

$pageTitle = "Review Management";

$database = new Database();
$connection = $database->connect();

$reviewRepository = new ReviewRepository($connection);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {

    $reviewId = filter_var(
        $_POST['review_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if ($reviewId !== false && $reviewId !== null && $reviewId > 0) {

        if ($reviewRepository->deleteReview($reviewId)) {
            $message = "Review deleted successfully.";
        } else {
            $message = "Unable to delete review.";
        }

    } else {
        $message = "Invalid review ID.";
    }
}

$reviews = $reviewRepository->getAllReviews();

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
        <h2>Review Management</h2>
    </header>

    <main class="review-management">

        <a href="dashboard.php">Back to Dashboard</a>

        <h2>Customer Reviews</h2>

        <p>View and manage feedback submitted by customers.</p>

        <?php if ($message !== ""): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <table>

            <thead>
                <tr>
                    <th>Date Submitted</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($reviews)): ?>

                    <tr>
                        <td colspan="4">No customer reviews to display yet.</td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($reviews as $review): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars(
                                    date('M d, Y', strtotime($review->getCreatedAt()))
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars((string) $review->getRating()) ?>/5
                            </td>

                            <td>
                                <?= htmlspecialchars($review->getComment()) ?>
                            </td>

                            <td>
                                <form method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this review?');">

                                    <input
                                        type="hidden"
                                        name="review_id"
                                        value="<?= htmlspecialchars((string) $review->getReviewId()) ?>"
                                    >

                                    <button type="submit" name="delete_review">
                                        Delete
                                    </button>

                                </form>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </main>

</body>

</html>
