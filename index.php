<?php
require 'autoload.php';

use App\Models\Admin;
use App\Models\RegularUser;
use App\Services\AuthService;
use App\Services\DatabaseService;

$dbService = new DatabaseService();

$admin = new Admin("Alice", "alice@example.com", "admin123");
if ($admin->saveToDatabase($dbService)) {
    echo "Admin saved to database successfully.<br>";
} else {
    echo "Failed to save admin to database.<br>";
}

$user = new RegularUser("Bob", "bob@example.com", "user123");
if ($user->saveToDatabase($dbService)) {
    echo "Regular user saved to database successfully.<br>";
} else {
    echo "Failed to save regular user to database.<br>";
}

$authService = new AuthService();

echo $authService->authenticate($admin, "alice@example.com", "admin123") . "<br>";

echo $authService->authenticate($user, "bob@example.com", "user123") . "<br>";

echo $admin->logout();
?>