<?php
$pageTitle = "Home";
require_once __DIR__ . '/../includes/header.php';
?>

<main>
    <section class="hero" id="home">
        <div class="wrap">
            <div class="hero-content">
                <span class="eyebrow">WELCOME TO TIARA'S CAFÉ</span>
                <h1>A Little Taste of Happiness.</h1>
                <p>Good food, great coffee, and memorable moments. Discover a place where every cup and every bite is made to brighten your day.</p>
                <div class="hero-actions">
                    <a href="#menu" class="btn btn-primary">Explore Our Menu</a>
                    <a href="#about" class="btn btn-ghost">Discover More</a>
                </div>
            </div>
        </div>
    </section>

    <section class="pad" id="about">
        <div class="wrap">
            <div class="sec-head">
                <span>OUR STORY</span>
                <h2>Discover Tiara's Café</h2>
                <p>A welcoming place for good food, refreshing drinks, and memorable moments.</p>
            </div>

            <div class="about-grid">
                <div class="story">
                    <span class="eyebrow">EST. 2023</span>
                    <h3>A Little Place Made for Everyone</h3>
                    <p>Tiara's Café was created to bring people together through good food, delicious drinks, and warm service.</p>
                    <p>Whether you're catching up with friends, taking a break, or celebrating a special occasion, we're here to make every visit memorable.</p>
                </div>

                <div class="gallery">
                    <img src="images/story-01.jpg" alt="Tiara's Café">
                    <img src="images/story-05.jpg" alt="Café drinks">
                    <img src="images/story-02.jpg" alt="Café experience">
                    <img src="images/story-12.jpg" alt="Café food">
                    <img src="images/story-08.jpg" alt="Café interior">
                    <img src="images/story-04.jpg" alt="Café selection">
                    <img src="images/story-11.jpg" alt="Café refreshments">
                    <img src="images/story-06.jpg" alt="Café moments">
                </div>
            </div>
        </div>
    </section>

    <section class="why pad" id="why">
        <div class="wrap">
            <div class="sec-head">
                <span>WHY TIARA'S CAFÉ</span>
                <h2>More Than Just a Café</h2>
                <p>Here's what makes every experience special.</p>
            </div>

            <div class="why-grid">
                <article>
                    <h3>Quality Drinks</h3>
                    <p>Enjoy carefully prepared drinks made for a satisfying experience.</p>
                </article>
                <article>
                    <h3>Perfect for Every Occasion</h3>
                    <p>A welcoming place for casual visits and special celebrations.</p>
                </article>
                <article>
                    <h3>A Variety of Choices</h3>
                    <p>Discover different drinks and food to suit your preferences.</p>
                </article>
                <article>
                    <h3>Friendly Service</h3>
                    <p>We aim to make every customer feel welcome and appreciated.</p>
                </article>
                <article>
                    <h3>Memorable Café Experience</h3>
                    <p>Enjoy good food, refreshing drinks, and moments worth remembering.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="pad" id="menu">
        <div class="wrap">
            <div class="sec-head">
                <span>OUR SELECTION</span>
                <h2>Explore Our Menu</h2>
                <p>Find something delicious to enjoy at Tiara's Café.</p>
            </div>

            <form class="search" action="index.php" method="GET">
                <input type="hidden" name="section" value="menu">
                <input type="text" name="search" placeholder="Search menu items..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="items">
                <?php
                require_once __DIR__ . '/../app/Repositories/MenuRepository.php';

                try {
                    $menuRepository = new \App\Repositories\MenuRepository();
                    $menuItems = $menuRepository->getAllMenuItems();
                    $searchTerm = trim($_GET['search'] ?? '');
                    $hasResults = false;

                    foreach ($menuItems as $item):
                        $itemName = $item->getItemName();
                        if ($searchTerm !== '' && stripos($itemName, $searchTerm) === false) {
                            continue;
                        }
                        $hasResults = true;
                ?>
                    <article class="item">
                        <div class="tile">
                            <?php if ($item->getImageUrl()): ?>
                                <img src="images/<?= htmlspecialchars(basename($item->getImageUrl())) ?>" alt="<?= htmlspecialchars($itemName) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="item-info">
                            <h3><?= htmlspecialchars($itemName) ?></h3>
                            <p><?= htmlspecialchars($item->getDescription()) ?></p>
                            <?php foreach ($item->getVariants() as $variant): ?>
                                <p class="price">
                                    <?= htmlspecialchars($variant->getSize()) ?> —
                                    ₱<?= number_format((float) $variant->getPrice(), 2) ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php
                    endforeach;

                    if (!$hasResults):
                ?>
                    <p>No menu items found.</p>
                <?php
                    endif;
                } catch (Throwable $e) {
                    echo '<p>The menu is temporarily unavailable.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="offers pad" id="offers">
        <div class="wrap">
            <div class="sec-head">
                <span>CELEBRATE WITH US</span>
                <h2>What We Offer</h2>
                <p>Make your gatherings extra special with our café services.</p>
            </div>

            <div class="occasions">
                <p>Birthdays</p>
                <p>Meetings</p>
                <p>Celebrations</p>
                <p>Private Events</p>
            </div>

            <div class="info-grid">
                <?php
                require_once __DIR__ . '/../app/Database/Database.php';

                try {
                    $pdo = (new \App\Database\Database())->getConnection();
                    $packages = $pdo->query("SELECT pakage_name, pakage_desc, pakage_price, pakage_inclusions FROM package")->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($packages as $package):
                ?>
                    <article class="pkg">
                        <h3><?= htmlspecialchars($package['pakage_name']) ?></h3>
                        <p><?= htmlspecialchars($package['pakage_desc']) ?></p>
                        <strong>₱<?= number_format((float) $package['pakage_price'], 2) ?></strong>
                        <p><?= nl2br(htmlspecialchars($package['pakage_inclusions'])) ?></p>
                    </article>
                <?php
                    endforeach;
                } catch (Throwable $e) {
                    echo '<p>Our packages are temporarily unavailable.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="pad" id="reservation">
        <div class="wrap">
            <div class="sec-head">
                <span>BOOK WITH US</span>
                <h2>Make Your Events Special</h2>
                <p>Interested in booking Tiara's Café for an event? Send us a reservation request.</p>
            </div>

            <div class="wizard">
                <form action="reservation.php" method="GET">
                    <button type="submit" class="btn btn-primary">View Reservation Form</button>
                </form>
                <p>Reservation requests are subject to confirmation by our administrator.</p>
            </div>
        </div>
    </section>

    <section class="reviews pad" id="reviews">
        <div class="wrap">
            <div class="sec-head">
                <span>YOUR EXPERIENCE</span>
                <h2>Customer Reviews</h2>
                <p>Read what customers have shared about their experience.</p>
            </div>

            <div class="reviews-grid">
                <div class="review-list">
                    <?php
                    require_once __DIR__ . '/../app/Repositories/ReviewRepository.php';

                    try {
                        $reviewRepository = new \App\Repositories\ReviewRepository();
                        $reviews = $reviewRepository->getAllReviews();

                        if (empty($reviews)):
                    ?>
                        <p>No reviews yet. Be the first to share your experience!</p>
                    <?php
                        else:
                            foreach ($reviews as $review):
                    ?>
                        <article class="review-card">
                            <p><?= str_repeat('★', (int) $review->getRating()) ?><?= str_repeat('☆', 5 - (int) $review->getRating()) ?></p>
                            <p><?= htmlspecialchars($review->getComment()) ?></p>
                        </article>
                    <?php
                            endforeach;
                        endif;
                    } catch (Throwable $e) {
                        echo '<p>Reviews are temporarily unavailable.</p>';
                    }
                    ?>
                </div>

                <div class="review-form">
                    <h3>Share Your Experience</h3>
                    <form action="reviews.php" method="GET">
                        <button type="submit" class="btn btn-primary">Write a Review</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
