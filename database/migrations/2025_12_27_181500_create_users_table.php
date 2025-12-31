<?php

return [
    'up' => function($pdo) {
        $sql = "
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );
        ";
        $pdo->exec($sql);
    },
    'down' => function($pdo) {
        $sql = "DROP TABLE IF EXISTS users;";
        $pdo->exec($sql);
    }
];
