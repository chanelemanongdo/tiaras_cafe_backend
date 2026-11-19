<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Controllers\AdminController;

$adminController = new AdminController();
$adminController->logout();

header("Location: login.php");
exit;