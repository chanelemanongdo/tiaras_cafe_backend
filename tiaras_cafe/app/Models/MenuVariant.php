<?php

namespace App\Models;

class MenuVariant
{
    private ?int $menuVariantId;
    private string $size;
    private float $price;

    public function __construct(
        string $size,
        float $price,
        ?int $menuVariantId = null
    ) {
        $this->menuVariantId = $menuVariantId;
        $this->size = $size;
        $this->price = $price;
    }

    public function getMenuVariantId(): ?int { return $this->menuVariantId; }
    public function getSize(): string { return $this->size; }
    public function getPrice(): float { return $this->price; }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}