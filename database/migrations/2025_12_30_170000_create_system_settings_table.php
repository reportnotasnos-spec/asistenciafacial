<?php

return [
    'up' => function($pdo) {
        $sql = "
        CREATE TABLE IF NOT EXISTS system_settings (
            setting_key VARCHAR(100) PRIMARY KEY,
            setting_value TEXT,
            setting_group VARCHAR(50) DEFAULT 'general',
            description VARCHAR(255),
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );

        INSERT INTO system_settings (setting_key, setting_value, setting_group, description) VALUES 
        ('site_name', 'Asistencia Facial', 'general', 'Name of the application shown in the header'),
        ('attendance_late_threshold', '15', 'attendance', 'Minutes after start time to mark as Late'),
        ('attendance_absent_threshold', '30', 'attendance', 'Minutes after start time to mark as Absent (if not checked)'),
        ('allow_student_registration', '1', 'auth', 'Allow new students to register through the portal'),
        ('institution_logo_text', 'NOS', 'general', 'Short text/acronym for the institution');
        ";
        $pdo->exec($sql);
    },
    'down' => function($pdo) {
        $sql = "DROP TABLE IF EXISTS system_settings;";
        $pdo->exec($sql);
    }
];
