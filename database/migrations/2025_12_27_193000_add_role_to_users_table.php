<?php

return [
    'up' => function($pdo) {
        $sql = "
        ALTER TABLE users
        ADD COLUMN role ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'student' AFTER password;
        ";
        $pdo->exec($sql);
    },
    'down' => function($pdo) {
        $sql = "
        ALTER TABLE users
        DROP COLUMN role;
        ";
        $pdo->exec($sql);
    }
];
