<?php

return [
    'up' => function($conn) {
        // 1. Study Programs
        $conn->exec("CREATE TABLE IF NOT EXISTS study_programs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            code VARCHAR(20) UNIQUE
        ) ENGINE=INNODB");

        // 2. Academic Periods
        $conn->exec("CREATE TABLE IF NOT EXISTS academic_periods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            is_active BOOLEAN DEFAULT 0
        ) ENGINE=INNODB");

        // 3. Rooms
        $conn->exec("CREATE TABLE IF NOT EXISTS rooms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            capacity INT,
            location VARCHAR(100)
        ) ENGINE=INNODB");

        // 4. Subjects
        $conn->exec("CREATE TABLE IF NOT EXISTS subjects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            program_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            code VARCHAR(20),
            credits INT DEFAULT 3,
            FOREIGN KEY (program_id) REFERENCES study_programs(id) ON DELETE CASCADE
        ) ENGINE=INNODB");

        // 5. Courses (Instancias)
        $conn->exec("CREATE TABLE IF NOT EXISTS courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            subject_id INT NOT NULL,
            period_id INT NOT NULL,
            teacher_id INT NOT NULL,
            group_name VARCHAR(50),
            FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
            FOREIGN KEY (period_id) REFERENCES academic_periods(id) ON DELETE CASCADE,
            FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=INNODB");

        // 6. Class Sessions (Calendario)
        $conn->exec("CREATE TABLE IF NOT EXISTS class_sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT NOT NULL,
            room_id INT NULL,
            specific_date DATE NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            status ENUM('scheduled', 'cancelled', 'completed') DEFAULT 'scheduled',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
            INDEX (specific_date, start_time)
        ) ENGINE=INNODB");

        // 7. Enrollments
        $conn->exec("CREATE TABLE IF NOT EXISTS course_enrollments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT NOT NULL,
            student_id INT NOT NULL,
            enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(course_id, student_id),
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=INNODB");

        // 8. Attendance Logs (New Version)
        $conn->exec("CREATE TABLE IF NOT EXISTS attendance_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id INT NOT NULL,
            student_id INT NOT NULL,
            scan_time DATETIME NOT NULL,
            status ENUM('present', 'late', 'absent') DEFAULT 'present',
            verification_method VARCHAR(50) DEFAULT 'face_id',
            FOREIGN KEY (session_id) REFERENCES class_sessions(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE(session_id, student_id)
        ) ENGINE=INNODB");
    },
    'down' => function($conn) {
        $conn->exec("DROP TABLE IF EXISTS attendance_logs");
        $conn->exec("DROP TABLE IF EXISTS course_enrollments");
        $conn->exec("DROP TABLE IF EXISTS class_sessions");
        $conn->exec("DROP TABLE IF EXISTS courses");
        $conn->exec("DROP TABLE IF EXISTS subjects");
        $conn->exec("DROP TABLE IF EXISTS rooms");
        $conn->exec("DROP TABLE IF EXISTS academic_periods");
        $conn->exec("DROP TABLE IF EXISTS study_programs");
    }
];