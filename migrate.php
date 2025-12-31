<?php

// A simple PHP migration runner

// Load configuration
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/core/Database.php';

echo "Migration script started.\n";

try {
    // Get PDO instance from our Database class
    $pdo = (new Database())->getDbh(); // We need to expose the handler
    if (!$pdo) {
        echo "Failed to get PDO instance. Exiting.\n";
        exit(1);
    }
    echo "Database connection successful.\n";

    // 1. Create migrations table if it doesn't exist
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );
    echo "Checked/created 'migrations' table.\n";

    // 2. Get all migrations that have already been run
    $runMigrations = $pdo->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);
    $runMigrations = $runMigrations ?: [];

    // 3. Get all migration files
    $migrationFiles = glob(__DIR__ . '/database/migrations/*.php');
    if (empty($migrationFiles)) {
        echo "No migration files found.\n";
    } else {
        echo "Found " . count($migrationFiles) . " migration files.\n";
    }

    // 4. Run migrations that haven't been run yet
    $migrationsRun = 0;
    foreach ($migrationFiles as $file) {
        $migrationName = basename($file);
        
        if (!in_array($migrationName, $runMigrations)) {
            echo "Running migration: {$migrationName}...\n";
            
            $migration = require $file;
            
            if (is_array($migration) && isset($migration['up']) && is_callable($migration['up'])) {
                $migration['up']($pdo);
                
                // Record the migration
                $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
                $stmt->execute([$migrationName]);
                
                echo "Finished migration: {$migrationName}\n";
                $migrationsRun++;
            } else {
                echo "Warning: Invalid migration file format for {$migrationName}. Skipping.\n";
            }
        }
    }

    if ($migrationsRun > 0) {
        echo "Successfully ran {$migrationsRun} new migrations.\n";
    } else {
        echo "No new migrations to run. Database is up to date.\n";
    }

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    exit(1);
}
