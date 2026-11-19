<?php

namespace App\Models;

class Admin
{
    private ?int $adminId;
    private string $username;
    private string $email;
    private string $password;
    private ?string $createdAt;

    public function __construct(
        string $username,
        string $email,
        string $password,
        ?int $adminId = null,
        ?string $createdAt = null
    ) {
        $this->adminId = $adminId;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    public function getAdminId(): ?int
    {
        return $this->adminId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
}