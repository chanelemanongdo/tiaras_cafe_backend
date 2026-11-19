<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Controllers\AdminController;

$adminController = new AdminController();

if (!$adminController->isAuthenticated()) {
    header("Location: login.php");
    exit;
}