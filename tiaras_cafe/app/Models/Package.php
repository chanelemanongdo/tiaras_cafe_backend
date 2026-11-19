<?php

namespace App\Models;

class Package
{
    private ?int $packageId;
    private string $packageName;
    private string $packageDesc;
    private float $packagePrice;
    private string $packageInclusions;

    public function __construct(
        string $packageName,
        string $packageDesc,
        float $packagePrice,
        string $packageInclusions,
        ?int $packageId = null
    ) {
        $this->packageId = $packageId;
        $this->packageName = $packageName;
        $this->packageDesc = $packageDesc;
        $this->packagePrice = $packagePrice;
        $this->packageInclusions = $packageInclusions;
    }

    public function getPackageId(): ?int { return $this->packageId; }
    public function getPackageName(): string { return $this->packageName; }
    public function getPackageDesc(): string { return $this->packageDesc; }
    public function getPackagePrice(): float { return $this->packagePrice; }
    public function getPackageInclusions(): string { return $this->packageInclusions; }
}