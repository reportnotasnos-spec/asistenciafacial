<?php

return [
    'up' => function($pdo) {
        $sql = "
        CREATE TABLE teacher_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            employee_id_number VARCHAR(20) UNIQUE,
            department VARCHAR(100),
            specialization VARCHAR(100),
            hire_date DATE,
            office_location VARCHAR(50),
            contact_phone VARCHAR(20) DEFAULT NULL,
            bio TEXT DEFAULT NULL,
            profile_picture_url VARCHAR(255) DEFAULT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
        ";
        $pdo->exec($sql);
    },
    'down' => function($pdo) {
        $sql = "DROP TABLE IF EXISTS teacher_details;";
        $pdo->exec($sql);
    }
];
