<?php

namespace App\Repositories;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\MenuVariant;
use PDO;

class MenuRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllMenuVariants(): array
    {
        $sql = "
            SELECT menu_variant_id, size, price
            FROM menu_variant
            ORDER BY size, price
        ";

        $statement = $this->connection->query($sql);
        $rows = $statement->fetchAll();

        $variants = [];

        foreach ($rows as $row) {
            $variants[] = new MenuVariant(
                (string) $row['size'],
                (float) $row['price'],
                (int) $row['menu_variant_id']
            );
        }

        return $variants;
    }

    public function getAllMenuItems(): array
    {
        $sql = "
            SELECT
                mi.menu_item_id,
                mi.Item_name,
                mi.item_desc,
                mi.is_available,
                mi.image_url,
                c.category_id,
                c.category_name,
                c.category_desc,
                mv.menu_variant_id,
                mv.size,
                mv.price
            FROM menu_item mi
            INNER JOIN category c
                ON mi.category_id = c.category_id
            INNER JOIN menu m
                ON mi.menu_item_id = m.menu_item_id
            INNER JOIN menu_variant mv
                ON m.menu_variant_id = mv.menu_variant_id
            ORDER BY mi.menu_item_id, mv.menu_variant_id
        ";

        $statement = $this->connection->query($sql);
        $rows = $statement->fetchAll();

        $menuItems = [];

        foreach ($rows as $row) {
            $id = (int) $row['menu_item_id'];

            if (!isset($menuItems[$id])) {
                $category = new Category(
                    $row['category_name'],
                    (string) ($row['category_desc'] ?? ''),
                    (int) $row['category_id']
                );

                $menuItems[$id] = new MenuItem(
                    $row['Item_name'],
                    $row['item_desc'],
                    $category,
                    $row['is_available'] === 'available',
                    $id,
                    $row['image_url']
                );
            }

            $variant = new MenuVariant(
                (string) $row['size'],
                (float) $row['price'],
                (int) $row['menu_variant_id']
            );

            $menuItems[$id]->addVariant($variant);
        }

        return array_values($menuItems);
    }

    private function linkVariantToMenuItem(
        int $menuItemId,
        MenuVariant $variant
    ): void {
        $variantId = $variant->getMenuVariantId();

        if ($variantId === null) {
            $sql = "
                INSERT INTO menu_variant (size, price)
                VALUES (:size, :price)
            ";

            $statement = $this->connection->prepare($sql);

            $statement->execute([
                ':size' => $variant->getSize(),
                ':price' => $variant->getPrice()
            ]);

            $variantId = (int) $this->connection->lastInsertId();

        } else {
            $sql = "
                SELECT menu_variant_id
                FROM menu_variant
                WHERE menu_variant_id = :variant_id
            ";

            $statement = $this->connection->prepare($sql);

            $statement->execute([
                ':variant_id' => $variantId
            ]);

            if (!$statement->fetch()) {
                throw new \InvalidArgumentException(
                    "The selected menu variant does not exist."
                );
            }
        }

        $sql = "
            INSERT INTO menu (menu_item_id, menu_variant_id)
            VALUES (:menu_item_id, :menu_variant_id)
        ";

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            ':menu_item_id' => $menuItemId,
            ':menu_variant_id' => $variantId
        ]);
    }

    public function addMenuItem(MenuItem $menuItem): bool
    {
        try {
            $this->connection->beginTransaction();

            $categoryId = $menuItem->getCategory()?->getCategoryId();

            if ($categoryId === null || $menuItem->getVariants() === []) {
                throw new \InvalidArgumentException(
                    "A category and at least one variant are required."
                );
            }

            $sql = "
                INSERT INTO menu_item
                    (category_id, Item_name, item_desc, is_available, image_url)
                VALUES
                    (:category_id, :item_name, :item_desc, :is_available, :image_url)
            ";

            $statement = $this->connection->prepare($sql);

            $statement->execute([
                ':category_id' => $categoryId,
                ':item_name' => $menuItem->getItemName(),
                ':item_desc' => $menuItem->getItemDesc(),
                ':is_available' => $menuItem->isAvailable()
                    ? 'available'
                    : 'unavailable',
                ':image_url' => $menuItem->getImageUrl()
            ]);

            $menuItemId = (int) $this->connection->lastInsertId();

            foreach ($menuItem->getVariants() as $variant) {
                $this->linkVariantToMenuItem($menuItemId, $variant);
            }

            $this->connection->commit();

            return true;

        } catch (\Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            error_log("Adding menu item failed: " . $e->getMessage());

            return false;
        }
    }

    public function updateMenuItem(MenuItem $menuItem): bool
    {
        try {
            $this->connection->beginTransaction();

            if (
                $menuItem->getCategory() === null ||
                $menuItem->getVariants() === []
            ) {
                throw new \InvalidArgumentException(
                    "A category and at least one variant are required."
                );
            }

            $sql = "
                UPDATE menu_item
                SET category_id = :category_id,
                    Item_name = :item_name,
                    item_desc = :item_desc,
                    is_available = :is_available,
                    image_url = :image_url
                WHERE menu_item_id = :menu_item_id
            ";

            $statement = $this->connection->prepare($sql);

            $statement->execute([
                ':category_id' => $menuItem->getCategory()->getCategoryId(),
                ':item_name' => $menuItem->getItemName(),
                ':item_desc' => $menuItem->getItemDesc(),
                ':is_available' => $menuItem->isAvailable()
                    ? 'available'
                    : 'unavailable',
                ':image_url' => $menuItem->getImageUrl(),
                ':menu_item_id' => $menuItem->getMenuItemId()
            ]);

            $deleteSql = "
                DELETE FROM menu
                WHERE menu_item_id = :menu_item_id
            ";

            $deleteStatement = $this->connection->prepare($deleteSql);

            $deleteStatement->execute([
                ':menu_item_id' => $menuItem->getMenuItemId()
            ]);

            foreach ($menuItem->getVariants() as $variant) {
                $this->linkVariantToMenuItem(
                    $menuItem->getMenuItemId(),
                    $variant
                );
            }

            $this->connection->commit();

            return true;

        } catch (\Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            error_log("Updating menu item failed: " . $e->getMessage());

            return false;
        }
    }

    public function deleteMenuItem(int $menuItemId): bool
    {
        try {
            $this->connection->beginTransaction();

            $sql = "
                DELETE FROM menu
                WHERE menu_item_id = :menu_item_id
            ";

            $statement = $this->connection->prepare($sql);

            $statement->execute([
                ':menu_item_id' => $menuItemId
            ]);

            $sql = "
                DELETE FROM menu_item
                WHERE menu_item_id = :menu_item_id
            ";

            $statement = $this->connection->prepare($sql);

            $statement->execute([
                ':menu_item_id' => $menuItemId
            ]);

            $this->connection->commit();

            return true;

        } catch (\Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            error_log("Deleting menu item failed: " . $e->getMessage());

            return false;
        }
    }
}
