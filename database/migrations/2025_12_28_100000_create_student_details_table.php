<?php

return [
    'up' => function($pdo) {
        $sql = "
        CREATE TABLE student_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            student_id_number VARCHAR(20) UNIQUE,
            date_of_birth DATE,
            grade_level VARCHAR(50),
            enrollment_date DATE,
            emergency_contact_name VARCHAR(255),
            emergency_contact_phone VARCHAR(20),
            profile_picture_url VARCHAR(255) DEFAULT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
        ";
        $pdo->exec($sql);
    },
    'down' => function($pdo) {
        $sql = "DROP TABLE IF EXISTS student_details;";
        $pdo->exec($sql);
    }
];
