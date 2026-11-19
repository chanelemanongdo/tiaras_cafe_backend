<?php

namespace App\Controllers;

use App\Models\MenuItem;
use App\Repositories\MenuRepository;

class MenuController
{
    private array $menuItems = [];

    public function loadMenuFromDatabase(MenuRepository $repository): void
    {
        $this->menuItems = $repository->getAllMenuItems();
    }

    public function setMenuItems(array $menuItems): void
    {
        $this->menuItems = $menuItems;
    }

    public function addMenuItem(MenuItem $menuItem): void
    {
        $this->menuItems[] = $menuItem;
    }

    public function getAllMenuItems(): array
    {
        return $this->menuItems;
    }

    public function searchMenuItems(string $keyword): array
    {
        $results = [];

        foreach ($this->menuItems as $item) {
            if (stripos($item->getItemName(), trim($keyword)) !== false) {
                $results[] = $item;
            }
        }

        return $results;
    }

    public function filterMenuItems(
        ?string $categoryName = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $size = null
    ): array {
        if (
            $minPrice !== null &&
            $maxPrice !== null &&
            $minPrice > $maxPrice
        ) {
            return [];
        }

        $variants = [];

        foreach ($this->menuItems as $item) {
            foreach ($item->getVariants() as $variant) {
                $variants[] = [
                    'item' => $item,
                    'variant' => $variant
                ];
            }
        }

        if ($categoryName !== null && trim($categoryName) !== '') {
            $variants = $this->filterByField(
                $variants,
                'category',
                strtolower(trim($categoryName)),
                strtolower(trim($categoryName))
            );
        }

        if ($minPrice !== null || $maxPrice !== null) {
            $variants = $this->filterByField(
                $variants,
                'price',
                $minPrice ?? -INF,
                $maxPrice ?? INF
            );
        }

        if ($size !== null && trim($size) !== '') {
            $variants = $this->filterByField(
                $variants,
                'size',
                strtolower(trim($size)),
                strtolower(trim($size))
            );
        }

        $results = [];
        $seen = [];

        foreach ($variants as $entry) {
            $item = $entry['item'];
            $id = spl_object_id($item);

            if (!isset($seen[$id])) {
                $results[] = $item;
                $seen[$id] = true;
            }
        }

        return $results;
    }

    public function filterByCategory(string $categoryName): array
    {
        return $this->filterMenuItems(categoryName: $categoryName);
    }

    public function filterByPrice(float $minPrice, float $maxPrice): array
    {
        return $this->filterMenuItems(
            minPrice: $minPrice,
            maxPrice: $maxPrice
        );
    }

    public function filterBySize(string $size): array
    {
        return $this->filterMenuItems(size: $size);
    }

    public function sortMenuItemsByPrice(): array
    {
        $variants = [];

        foreach ($this->menuItems as $item) {
            foreach ($item->getVariants() as $variant) {
                $variants[] = [
                    'item' => $item,
                    'variant' => $variant
                ];
            }
        }

        $variants = $this->mergeSortVariants($variants, 'price');

        $results = [];
        $seen = [];

        foreach ($variants as $entry) {
            $item = $entry['item'];
            $id = spl_object_id($item);

            if (!isset($seen[$id])) {
                $results[] = $item;
                $seen[$id] = true;
            }
        }

        return $results;
    }

    public function searchMenuItemByPrice(float $targetPrice): ?MenuItem
    {
        $results = $this->filterMenuItems(
            minPrice: $targetPrice,
            maxPrice: $targetPrice
        );

        return $results[0] ?? null;
    }

    private function filterByField(
        array $variants,
        string $field,
        string|float $minimum,
        string|float $maximum
    ): array {
        $variants = $this->mergeSortVariants($variants, $field);

        $start = $this->findLowerBound($variants, $field, $minimum);
        $end = $this->findUpperBound($variants, $field, $maximum);

        return array_slice($variants, $start, $end - $start);
    }

    private function getFieldValue(array $entry, string $field): string|float
    {
        if ($field === 'price') {
            return $entry['variant']->getPrice();
        }

        if ($field === 'size') {
            return strtolower($entry['variant']->getSize());
        }

        if ($field === 'category') {
            return strtolower(
                $entry['item']->getCategory()?->getCategoryName() ?? ''
            );
        }

        return '';
    }

    private function compareValues(
        string|float $first,
        string|float $second
    ): int {
        if (is_float($first) || is_float($second)) {
            return $first <=> $second;
        }

        return strcmp($first, $second);
    }

    private function findLowerBound(
        array $variants,
        string $field,
        string|float $target
    ): int {
        $left = 0;
        $right = count($variants);

        while ($left < $right) {
            $middle = (int) floor(($left + $right) / 2);

            if (
                $this->compareValues(
                    $this->getFieldValue($variants[$middle], $field),
                    $target
                ) < 0
            ) {
                $left = $middle + 1;
            } else {
                $right = $middle;
            }
        }

        return $left;
    }

    private function findUpperBound(
        array $variants,
        string $field,
        string|float $target
    ): int {
        $left = 0;
        $right = count($variants);

        while ($left < $right) {
            $middle = (int) floor(($left + $right) / 2);

            if (
                $this->compareValues(
                    $this->getFieldValue($variants[$middle], $field),
                    $target
                ) <= 0
            ) {
                $left = $middle + 1;
            } else {
                $right = $middle;
            }
        }

        return $left;
    }

    private function mergeSortVariants(
        array $variants,
        string $field
    ): array {
        if (count($variants) <= 1) {
            return $variants;
        }

        $middle = (int) floor(count($variants) / 2);

        $left = $this->mergeSortVariants(
            array_slice($variants, 0, $middle),
            $field
        );

        $right = $this->mergeSortVariants(
            array_slice($variants, $middle),
            $field
        );

        return $this->mergeVariants($left, $right, $field);
    }

    private function mergeVariants(
        array $left,
        array $right,
        string $field
    ): array {
        $result = [];
        $i = 0;
        $j = 0;

        while ($i < count($left) && $j < count($right)) {
            if (
                $this->compareValues(
                    $this->getFieldValue($left[$i], $field),
                    $this->getFieldValue($right[$j], $field)
                ) <= 0
            ) {
                $result[] = $left[$i];
                $i++;
            } else {
                $result[] = $right[$j];
                $j++;
            }
        }

        while ($i < count($left)) {
            $result[] = $left[$i];
            $i++;
        }

        while ($j < count($right)) {
            $result[] = $right[$j];
            $j++;
        }

        return $result;
    }

    public function removeMenuItem(int $index): void
    {
        if (isset($this->menuItems[$index])) {
            unset($this->menuItems[$index]);
            $this->menuItems = array_values($this->menuItems);
        }
    }
}
