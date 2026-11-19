<?php

namespace App\Models;

class Category
{
    private ?int $categoryId;
    private string $categoryName;
    private string $categoryDesc;

    public function __construct(
        string $categoryName,
        string $categoryDesc,
        ?int $categoryId = null
    ) {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->categoryDesc = $categoryDesc;
    }

    public function getCategoryId(): ?int { return $this->categoryId; }
    public function getCategoryName(): string { return $this->categoryName; }
    public function getCategoryDesc(): string { return $this->categoryDesc; }
}