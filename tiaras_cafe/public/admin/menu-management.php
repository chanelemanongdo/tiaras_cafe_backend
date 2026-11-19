<?php

require_once dirname(__DIR__, 2) . '/includes/admin_auth.php';

use App\Database\Database;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Repositories\MenuRepository;

$pageTitle = "Menu Management";

$database = new Database();
$pdo = $database->connect();

$message = "";
$categories = [];
$menuItems = [];
$existingVariants = [];
$editItem = null;

$repository = null;

if ($pdo !== null) {

    $categoryStatement = $pdo->query("
        SELECT category_id, category_name, category_desc
        FROM category
        ORDER BY category_name
    ");

    $categories = $categoryStatement->fetchAll();

    $repository = new MenuRepository($pdo);
    $existingVariants = $repository->getAllMenuVariants();
    $menuItems = $repository->getAllMenuItems();

    if (isset($_GET['edit'])) {
        $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);

        if ($editId !== false && $editId > 0) {
            foreach ($menuItems as $item) {
                if ($item->getMenuItemId() === $editId) {
                    $editItem = $item;
                    break;
                }
            }
        }

        if ($editItem === null) {
            $message = "Menu item not found.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $action = $_POST['action'] ?? '';

        if ($action === 'delete') {

            $deleteId = filter_var(
                $_POST['menu_item_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($deleteId && $repository->deleteMenuItem($deleteId)) {
                header("Location: menu-management.php?success=deleted");
                exit;
            }

            $message = "Unable to delete the menu item.";

        } elseif ($action === 'add' || $action === 'edit') {

            $itemId = filter_var(
                $_POST['menu_item_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            $itemName = trim($_POST['item_name'] ?? '');
            $itemDesc = trim($_POST['item_desc'] ?? '');
            $categoryId = (int) ($_POST['category_id'] ?? 0);
            $availability = isset($_POST['is_available']);
            $postedVariants = $_POST['variants'] ?? [];

            $selectedCategory = null;

            foreach ($categories as $categoryRow) {
                if ((int) $categoryRow['category_id'] === $categoryId) {
                    $selectedCategory = new Category(
                        $categoryRow['category_name'],
                        $categoryRow['category_desc'] ?? '',
                        (int) $categoryRow['category_id']
                    );
                    break;
                }
            }

            $variants = [];
            $variantError = false;

            if (is_array($postedVariants)) {

                foreach ($postedVariants as $row) {

                    if (!is_array($row)) {
                        $variantError = true;
                        break;
                    }

                    $mode = $row['mode'] ?? '';

                    if ($mode === 'existing') {

                        $variantId = filter_var(
                            $row['existing_variant_id'] ?? null,
                            FILTER_VALIDATE_INT
                        );

                        $foundVariant = null;

                        foreach ($existingVariants as $existingVariant) {
                            if (
                                $variantId !== false &&
                                $existingVariant->getMenuVariantId() === $variantId
                            ) {
                                $foundVariant = $existingVariant;
                                break;
                            }
                        }

                        if ($foundVariant === null) {
                            $variantError = true;
                            break;
                        }

                        $variants[] = new MenuVariant(
                            $foundVariant->getSize(),
                            $foundVariant->getPrice(),
                            $foundVariant->getMenuVariantId()
                        );

                    } elseif ($mode === 'new') {

                        $size = trim($row['size'] ?? '');
                        $price = trim((string) ($row['price'] ?? ''));

                        if (
                            $price === '' ||
                            !is_numeric($price) ||
                            (float) $price < 0
                        ) {
                            $variantError = true;
                            break;
                        }

                        $variants[] = new MenuVariant(
                            $size,
                            (float) $price
                        );

                    } else {
                        $variantError = true;
                        break;
                    }
                }
            } else {
                $variantError = true;
            }

            if ($itemName === '') {
                $message = "Please enter an item name.";

            } elseif ($selectedCategory === null) {
                $message = "Please select a valid category.";

            } elseif ($variantError || count($variants) === 0) {
                $message = "Please provide at least one valid menu variant.";

            } elseif (
                $action === 'edit' &&
                (!$itemId || $itemId <= 0)
            ) {
                $message = "Invalid menu item.";

            } else {

                $imageUrl = "";

                if ($action === 'edit') {

                    $originalItem = null;

                    foreach ($menuItems as $item) {
                        if ($item->getMenuItemId() === $itemId) {
                            $originalItem = $item;
                            break;
                        }
                    }

                    if ($originalItem === null) {
                        $message = "Menu item not found.";
                    } else {
                        $imageUrl = $originalItem->getImageUrl();
                    }
                }

                if ($message === "") {

                    $menuItem = new MenuItem(
                        $itemName,
                        $itemDesc,
                        $selectedCategory,
                        $availability,
                        $action === 'edit' ? $itemId : null,
                        $imageUrl
                    );

                    foreach ($variants as $variant) {
                        $menuItem->addVariant($variant);
                    }

                    if ($action === 'add') {

                        if ($repository->addMenuItem($menuItem)) {
                            header("Location: menu-management.php?success=added");
                            exit;
                        }

                        $message = "Unable to add the menu item.";

                    } else {

                        if ($repository->updateMenuItem($menuItem)) {
                            header("Location: menu-management.php?success=updated");
                            exit;
                        }

                        $message = "Unable to update the menu item.";
                    }
                }
            }
        }
    }

    if (isset($_GET['success'])) {
        switch ($_GET['success']) {
            case 'added':
                $message = "Menu item successfully added!";
                break;

            case 'updated':
                $message = "Menu item successfully updated!";
                break;

            case 'deleted':
                $message = "Menu item successfully deleted!";
                break;
        }
    }

    $menuItems = $repository->getAllMenuItems();

    if (isset($_GET['edit'])) {
        $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);

        foreach ($menuItems as $item) {
            if ($editId !== false && $item->getMenuItemId() === $editId) {
                $editItem = $item;
                break;
            }
        }
    }

} else {
    $message = "Unable to connect to the database.";
}

$isEditing = $editItem !== null;

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
    <h2>Menu Management</h2>
</header>

<main class="menu-management">

    <a href="dashboard.php">Back to Dashboard</a>

    <h2>
        <?= $isEditing ? 'Edit Menu Item' : 'Add Menu Item' ?>
    </h2>

    <?php if ($message !== ""): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form
        method="POST"
        action="<?= $isEditing
            ? 'menu-management.php?edit=' . (int) $editItem->getMenuItemId()
            : 'menu-management.php' ?>"
    >

        <input
            type="hidden"
            name="action"
            value="<?= $isEditing ? 'edit' : 'add' ?>"
        >

        <?php if ($isEditing): ?>
            <input
                type="hidden"
                name="menu_item_id"
                value="<?= (int) $editItem->getMenuItemId() ?>"
            >
        <?php endif; ?>

        <div>
            <label for="item_name">Item Name:</label>
            <input
                type="text"
                id="item_name"
                name="item_name"
                value="<?= htmlspecialchars($editItem?->getItemName() ?? '') ?>"
                required
            >
        </div>

        <div>
            <label for="item_desc">Description:</label>
            <textarea
                id="item_desc"
                name="item_desc"
                rows="3"
            ><?= htmlspecialchars($editItem?->getItemDesc() ?? '') ?></textarea>
        </div>

        <div>
            <label for="category_id">Category:</label>

            <select id="category_id" name="category_id" required>
                <option value="">Select Category</option>

                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?= (int) $category['category_id'] ?>"
                        <?= (
                            $editItem?->getCategory()?->getCategoryId()
                            === (int) $category['category_id']
                        ) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($category['category_name']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <div>
            <label>
                <input
                    type="checkbox"
                    name="is_available"
                    <?= (!$isEditing || $editItem->isAvailable()) ? 'checked' : '' ?>
                >
                Available
            </label>
        </div>

        <h3>Menu Variants</h3>

        <div id="variant-container">

            <?php if ($isEditing): ?>

                <?php foreach ($editItem->getVariants() as $index => $variant): ?>

                    <div class="variant-row">

                        <label>Variant Type:</label>

                        <select
                            class="variant-mode"
                            name="variants[<?= $index ?>][mode]"
                        >
                            <option value="existing" selected>
                                Reuse Existing Variant
                            </option>
                            <option value="new">
                                Create New Variant
                            </option>
                        </select>

                        <div class="existing-variant-fields">

                            <label>Existing Variant:</label>

                            <select
                                name="variants[<?= $index ?>][existing_variant_id]"
                                class="existing-variant-select"
                            >
                                <?php foreach ($existingVariants as $existingVariant): ?>
                                    <option
                                        value="<?= (int) $existingVariant->getMenuVariantId() ?>"
                                        <?= (
                                            $variant->getMenuVariantId()
                                            === $existingVariant->getMenuVariantId()
                                        ) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($existingVariant->getSize() ?: 'Regular') ?>
                                        - ₱<?= number_format($existingVariant->getPrice(), 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="new-variant-fields" hidden>

                            <label>New Size:</label>
                            <input
                                type="text"
                                name="variants[<?= $index ?>][size]"
                                placeholder="e.g. 24oz"
                                disabled
                            >

                            <label>New Price:</label>
                            <input
                                type="number"
                                name="variants[<?= $index ?>][price]"
                                min="0"
                                step="0.01"
                                placeholder="e.g. 85"
                                disabled
                            >

                        </div>

                        <button type="button" class="remove-variant">
                            Remove Variant
                        </button>

                        <hr>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

        <button type="button" id="add-variant">
            Add Another Variant
        </button>

        <button type="submit">
            <?= $isEditing ? 'Save Changes' : 'Add Menu Item' ?>
        </button>

        <?php if ($isEditing): ?>
            <a href="menu-management.php">Cancel Editing</a>
        <?php endif; ?>

    </form>

    <template id="variant-row-template">
        <div class="variant-row">

            <label>Variant Type:</label>

            <select class="variant-mode" name="variants[__INDEX__][mode]">
                <?php if (count($existingVariants) > 0): ?>
                    <option value="existing">Reuse Existing Variant</option>
                <?php endif; ?>
                <option value="new">Create New Variant</option>
            </select>

            <div class="existing-variant-fields">

                <label>Existing Variant:</label>

                <select
                    name="variants[__INDEX__][existing_variant_id]"
                    class="existing-variant-select"
                >
                    <?php foreach ($existingVariants as $variant): ?>
                        <option value="<?= (int) $variant->getMenuVariantId() ?>">
                            <?= htmlspecialchars($variant->getSize() ?: 'Regular') ?>
                            - ₱<?= number_format($variant->getPrice(), 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>

            <div class="new-variant-fields">

                <label>New Size:</label>

                <input
                    type="text"
                    name="variants[__INDEX__][size]"
                    placeholder="e.g. 24oz"
                    disabled
                >

                <label>New Price:</label>

                <input
                    type="number"
                    name="variants[__INDEX__][price]"
                    min="0"
                    step="0.01"
                    placeholder="e.g. 85"
                    disabled
                >

            </div>

            <button type="button" class="remove-variant">
                Remove Variant
            </button>

            <hr>

        </div>
    </template>

    <h2>Existing Menu Items</h2>

    <table>

        <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Price / Variants</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <?php if (count($menuItems) > 0): ?>

                <?php foreach ($menuItems as $item): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($item->getItemName()) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $item->getCategory()?->getCategoryName() ?? 'Uncategorized'
                            ) ?>
                        </td>

                        <td>
                            <?php foreach ($item->getVariants() as $variant): ?>
                                <?= htmlspecialchars($variant->getSize() ?: 'Regular') ?>
                                - ₱<?= number_format($variant->getPrice(), 2) ?>
                                <br>
                            <?php endforeach; ?>
                        </td>

                        <td>
                            <?= $item->isAvailable() ? 'Available' : 'Unavailable' ?>
                        </td>

                        <td>

                            <a href="menu-management.php?edit=<?= (int) $item->getMenuItemId() ?>">
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="menu-management.php"
                                onsubmit="return confirm('Are you sure you want to delete this menu item?');"
                            >
                                <input type="hidden" name="action" value="delete">

                                <input
                                    type="hidden"
                                    name="menu_item_id"
                                    value="<?= (int) $item->getMenuItemId() ?>"
                                >

                                <button type="submit">Delete</button>
                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5">No menu items to display yet.</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</main>

<script>
    const variantContainer = document.getElementById('variant-container');
    const variantTemplate = document.getElementById('variant-row-template');
    const addVariantButton = document.getElementById('add-variant');

    let variantIndex = <?= $isEditing ? count($editItem->getVariants()) : 0 ?>;

    function updateVariantFields(row) {
        const mode = row.querySelector('.variant-mode').value;

        const existingFields = row.querySelector('.existing-variant-fields');
        const newFields = row.querySelector('.new-variant-fields');

        const existingSelect = row.querySelector('.existing-variant-select');
        const newInputs = newFields.querySelectorAll('input');

        existingFields.hidden = mode !== 'existing';
        newFields.hidden = mode !== 'new';

        existingSelect.disabled = mode !== 'existing';
        existingSelect.required = mode === 'existing';

        newInputs.forEach(input => {
            input.disabled = mode !== 'new';
            input.required = mode === 'new' && input.type === 'number';
        });
    }

    function setupVariantRow(row) {
        row.querySelector('.variant-mode').addEventListener('change', function () {
            updateVariantFields(row);
        });

        row.querySelector('.remove-variant').addEventListener('click', function () {
            row.remove();
        });

        updateVariantFields(row);
    }

    function addVariantRow() {
        const html = variantTemplate.innerHTML.replaceAll(
            '__INDEX__',
            variantIndex
        );

        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();

        const row = wrapper.firstElementChild;

        variantContainer.appendChild(row);

        setupVariantRow(row);

        variantIndex++;
    }

    document.querySelectorAll('.variant-row').forEach(setupVariantRow);

    addVariantButton.addEventListener('click', addVariantRow);

    <?php if (!$isEditing): ?>
        addVariantRow();
    <?php endif; ?>
</script>

</body>
</html>
