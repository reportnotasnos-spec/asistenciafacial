<?php

return [
    'up' => function($pdo) {
        $sql = "
        CREATE TABLE user_biometrics (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            biometric_data JSON NOT NULL,
            biometric_type VARCHAR(50) DEFAULT 'face_encoding',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
        ";
        $pdo->exec($sql);
    },
    'down' => function($pdo) {
        $sql = "DROP TABLE IF EXISTS user_biometrics;";
        $pdo->exec($sql);
    }
];
