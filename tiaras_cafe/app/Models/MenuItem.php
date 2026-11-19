<?php

namespace App\Models;

class MenuItem
{
    private ?int $menuItemId;
    private string $itemName;
    private string $itemDesc;
    private ?Category $category;
    private bool $isAvailable;
    private string $imageUrl;
    private array $variants = [];

    public function __construct(
        string $itemName,
        string $itemDesc,
        ?Category $category = null,
        bool $isAvailable = true,
        ?int $menuItemId = null,
        string $imageUrl = ""
    ) {
        $this->menuItemId = $menuItemId;
        $this->itemName = $itemName;
        $this->itemDesc = $itemDesc;
        $this->category = $category;
        $this->isAvailable = $isAvailable;
        $this->imageUrl = $imageUrl;
    }

    public function getMenuItemId(): ?int { return $this->menuItemId; }
    public function getItemName(): string { return $this->itemName; }
    public function getItemDesc(): string { return $this->itemDesc; }
    public function getCategory(): ?Category { return $this->category; }
    public function isAvailable(): bool { return $this->isAvailable; }
    public function getImageUrl(): string { return $this->imageUrl; }
    public function getVariants(): array { return $this->variants; }

    public function setItemName(string $itemName): void
    {
        $this->itemName = $itemName;
    }

    public function setItemDesc(string $itemDesc): void
    {
        $this->itemDesc = $itemDesc;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function setAvailability(bool $isAvailable): void
    {
        $this->isAvailable = $isAvailable;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function addVariant(MenuVariant $variant): void
    {
        $this->variants[] = $variant;
    }
}