<?php

return [
    'up' => function($pdo) {
        $sql = "
        CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT,
            type VARCHAR(50) DEFAULT 'info', -- info, warning, success, danger
            is_read TINYINT(1) DEFAULT 0,
            link VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
        ";
        $pdo->exec($sql);
    },
    'down' => function($pdo) {
        $sql = "DROP TABLE IF EXISTS notifications;";
        $pdo->exec($sql);
    }
];
