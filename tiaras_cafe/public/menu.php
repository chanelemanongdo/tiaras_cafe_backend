<?php
$pageTitle = "Our Menu";
require_once __DIR__ . '/../includes/header.php';

$menu = [
    "Coffee" => [
        ["Americano Caramel", "A smooth coffee with a sweet caramel flavor.", "16oz", 60, "22oz", 70],
        ["Americano Hazelnut", "A rich coffee with a nutty flavor.", "16oz", 60, "22oz", 70],
        ["Americano", "A classic coffee with a bold, rich taste.", "16oz", 60, "22oz", 70],
        ["Americano Vanilla", "A smooth coffee with a sweet vanilla flavor.", "16oz", 60, "22oz", 70],
        ["Ube Coffee", "A unique coffee with a creamy, sweet ube flavor.", "16oz", 60, "22oz", 70],
        ["Caramel Coffee", "A creamy coffee with a rich caramel taste.", "16oz", 60, "22oz", 70],
        ["Salted Caramel", "A balanced blend of sweet and salty flavors with coffee.", "16oz", 60, "22oz", 70],
        ["Vanilla Coffee", "A creamy coffee with a smooth vanilla taste.", "16oz", 60, "22oz", 70],
        ["Mocha Coffee", "A rich coffee with a sweet chocolate flavor.", "16oz", 60, "22oz", 70],
        ["Barista Drink (coffee latte)", "A smooth, creamy coffee perfect for everyday enjoyment.", "16oz", 60, "22oz", 70],
        ["Dirty Matcha", "A flavorful blend of coffee and matcha with a creamy taste.", "16oz", 60, "22oz", 70],
        ["Spanish Latte", "A rich and creamy coffee with a sweet, milky taste.", "16oz", 60, "22oz", 70],
        ["Salted Caramel", "A rich coffee drink and salted caramel flavors.", "16oz", 60, "22oz", 70],
        ["Choco Hazelnut Coffee", "A rich and creamy coffee with a smooth chocolate taste and a nutty hazelnut flavor.", "16oz", 60, "22oz", 70]
    ],
    "Non-Coffee Blends" => [
        ["Strawberry Mocha", "A creamy drink with sweet strawberry and chocolate flavors.", "16oz", 65, "22oz", 75],
        ["Strawberry Matcha", "A refreshing blend of sweet strawberry and earthy matcha flavors.", "16oz", 65, "22oz", 75],
        ["Matcha Caramel", "A creamy matcha drink with a sweet caramel taste.", "16oz", 65, "22oz", 75],
        ["Blueberry Matcha", "A creamy matcha drink with a sweet blueberry flavor.", "16oz", 65, "22oz", 75]
    ],
    "Non-Coffee" => [
        ["Choco Hazelnut Milk", "A creamy chocolate drink with a rich hazelnut flavor.", "16oz", 60, "22oz", 70],
        ["Ube Milk", "A smooth, creamy drink with a sweet ube flavor.", "16oz", 60, "22oz", 70],
        ["Mocha Milk", "A creamy milk drink with a rich chocolate flavor.", "16oz", 60, "22oz", 70],
        ["Matcha Milk", "A creamy drink with the distinct taste of matcha.", "16oz", 60, "22oz", 70],
        ["Blueberry Milk", "A sweet and creamy drink with a fruity blueberry flavor.", "16oz", 60, "22oz", 70],
        ["Strawberry Milk", "A creamy milk drink with a sweet strawberry taste.", "16oz", 60, "22oz", 70]
    ],
    "Fruit Soda" => [
        ["Lychee", "A bubbly, refreshing drink with a sweet and fruity taste.", "16oz", 50, "22oz", 60],
        ["Blueberry", "A fizzy drink with a sweet blueberry flavor.", "16oz", 50, "22oz", 60],
        ["Strawberry", "A refreshing soda with a sweet strawberry taste.", "16oz", 50, "22oz", 60],
        ["Green Apple", "A bubbly drink with a crisp and tangy green apple flavor.", "16oz", 50, "22oz", 60]
    ],
    "Snack" => [
        ["Pork Siomai", "Savory pork dumplings, perfect for a quick snack.", "4pcs", 25],
        ["Japanese Siomai", "Delicious Japanese-style dumplings with a savory taste.", "4pcs", 35],
        ["Gyoza", "Japanese-style dumplings with a satisfying savory flavor.", "4pcs", 35]
    ],
    "Pasta" => [
        ["Carbonara", "A creamy and savory pasta dish perfect for a satisfying meal.", "1 pack", 35],
        ["Spagetti", "A classic pasta dish with a delicious, savory flavor.", "1 pack", 35]
    ],
    "Noodles" => [
        ["Pancit Canton Original", "Classic stir-fried noodles with a savory taste.", "1 pack", 30],
        ["Pancit Canton Hot & Spicy", "Flavorful noodles with a bold and spicy kick.", "1 pack", 30],
        ["Pancit Canton Sweet & Spicy", "Delicious noodles with a balance of sweet and spicy flavors.", "1 pack", 30]
    ]
];
?>

<section class="menu-page">
    <div class="section-heading">
        <span>TIARA'S CAFÉ</span>
        <h1>Food and Drink Menu</h1>
        <p>Discover our selection of coffee, refreshing drinks, and delicious food.</p>
    </div>

    <?php foreach ($menu as $category => $items): ?>
        <div class="menu-category">
            <h2><?= htmlspecialchars($category) ?></h2>

            <div class="full-menu-grid">
                <?php foreach ($items as $item): ?>
                    <article class="full-menu-card">
                        <div class="full-menu-info">
                            <h3><?= htmlspecialchars($item[0]) ?></h3>
                            <p><?= htmlspecialchars($item[1]) ?></p>

                            <div class="menu-prices">
                                <?php if (count($item) === 6): ?>
                                    <span><?= htmlspecialchars($item[2]) ?> — ₱<?= number_format($item[3]) ?></span>
                                    <span><?= htmlspecialchars($item[4]) ?> — ₱<?= number_format($item[5]) ?></span>
                                <?php else: ?>
                                    <span><?= htmlspecialchars($item[2]) ?> — ₱<?= number_format($item[3]) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
