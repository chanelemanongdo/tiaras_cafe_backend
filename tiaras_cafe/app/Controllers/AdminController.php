<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Repositories\AdminRepository;

class AdminController
{
    private ?Admin $admin = null;

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setAdmin(Admin $admin): void
    {
        $this->admin = $admin;
    }

    public function loadAdminFromDatabase(
        string $username,
        AdminRepository $repository
    ): bool {
        $this->admin = $repository->findByUsernameOrEmail($username);

        return $this->admin !== null;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function verifyLogin(string $username, string $password): bool
    {
        if (
            trim($username) === "" ||
            trim($password) === "" ||
            $this->admin === null
        ) {
            return false;
        }

        $usernameMatches =
            $this->admin->getUsername() === $username ||
            $this->admin->getEmail() === $username;

        return $usernameMatches &&
            password_verify($password, $this->admin->getPassword());
    }

    public function login(
        string $username,
        string $password,
        AdminRepository $repository
    ): bool {
        if (!$this->loadAdminFromDatabase($username, $repository)) {
            return false;
        }

        if (!$this->verifyLogin($username, $password)) {
            return false;
        }

        $this->startSession();
        session_regenerate_id(true);

        $_SESSION['admin_id'] = $this->admin->getAdminId();
        $_SESSION['admin_username'] = $this->admin->getUsername();

        return true;
    }

    public function isAuthenticated(): bool
    {
        $this->startSession();

        return isset($_SESSION['admin_id']);
    }

    public function logout(): void
    {
        $this->startSession();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->admin = null;
    }
}
