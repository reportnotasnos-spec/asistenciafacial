<?php
/**
 * CLI Script for Auto-Closing Past Sessions
 * Can be run via Cron Job: * /15 * * * * php /path/to/project/cron_close_sessions.php
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once APP_ROOT . '/app/core/Autoloader.php';

Autoloader::register();

// Initialize Database to ensure constants are loaded
$db = new Database();

echo "Starting auto-close sessions process...\n";

$sessionModel = new ClassSession();
if ($sessionModel->closePastSessions()) {
    echo "Successfully updated past sessions to 'completed'.\n";
} else {
    echo "No sessions needed updating or an error occurred.\n";
}

echo "Process finished at " . date('Y-m-d H:i:s') . "\n";

