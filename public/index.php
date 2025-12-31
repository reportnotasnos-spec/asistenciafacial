<?php

require_once dirname(__DIR__) . '/config/app.php';

require_once dirname(__DIR__) . '/config/database.php';

try {
    // Load the autoloader
    require_once APP_ROOT . '/app/core/Autoloader.php';
    Autoloader::register();

    // Load Helpers
    require_once APP_ROOT . '/app/helpers/functions.php';

    // Start Session
    Session::start();

    // Load the routes definition file
    require_once APP_ROOT . '/routes/routes.php';

    // Dispatch the route
    $uri = Route::getUri();
    $method = Route::getMethod();
    Route::dispatch($uri, $method);

} catch (Exception $e) {
    // Log the error
    error_log("[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    
    // Show 500 Page
    $errorMessage = $e->getMessage(); // Pass to view
    
    // Define DEBUG_MODE if not defined (fallback)
    if (!defined('DEBUG_MODE')) define('DEBUG_MODE', false);
    
    // Check if view exists, otherwise echo simple error
    $errorView = APP_ROOT . '/resources/views/errors/500.php';
    if (file_exists($errorView)) {
        require_once $errorView;
    } else {
        http_response_code(500);
        echo "<h1>500 Server Error</h1>";
        if (DEBUG_MODE) echo "<p>" . $e->getMessage() . "</p>";
    }
    exit;
}
