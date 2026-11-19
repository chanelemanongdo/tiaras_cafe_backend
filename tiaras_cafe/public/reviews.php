<?php
$pageTitle = "Reviews";
require_once __DIR__ . '/../includes/header.php';
?>

<section class="reviews-page">

    <div class="reviews-hero">
        <span>TIARA'S CAFÉ</span>
        <h1>Customer Reviews</h1>
        <p>Share your experience with Tiara's Café and let us know what you think.</p>
        <p>We value your feedback!</p>
    </div>

    <div class="review-form-section">

        <div class="review-list">
            <h2>What Our Customers Say</h2>
            <p>Customer reviews will appear here.</p>
        </div>

        <h2>Share Your Experience</h2>

        <form method="POST" action="reviews.php">

            <div class="review-form-group">
                <label for="rating">Your Rating</label>

                <select id="rating" name="rating" required>
                    <option value="">Select a rating</option>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                </select>
            </div>

            <div class="review-form-group">
                <label for="comment">Your Comment</label>

                <textarea
                    id="comment"
                    name="comment"
                    rows="5"
                    placeholder="Tell us about your experience..."
                    required
                ></textarea>
            </div>

            <button type="submit" class="btn">Submit Review</button>

        </form>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

