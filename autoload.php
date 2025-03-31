<?php
// First, manually include the core classes in the correct order
require_once __DIR__ . '/App/Core/AbstractUser.php';
require_once __DIR__ . '/App/Core/AuthInterface.php';
require_once __DIR__ . '/App/Core/LoggerTrait.php';

// Then, set up the autoloader for the rest of the classes
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';

    // Check if file exists and include it
    if (file_exists($file)) {
        require_once $file;
    }
});
?>