<?php

return [
    'up' => function($pdo) {
        // Crear tabla de estadísticas
        $pdo->exec("CREATE TABLE IF NOT EXISTS course_stats (
            course_id INT PRIMARY KEY,
            total_sessions INT DEFAULT 0,
            total_attendances INT DEFAULT 0,
            last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
        )");

        // Poblar datos iniciales
        $pdo->exec("INSERT INTO course_stats (course_id, total_sessions, total_attendances)
                   SELECT 
                        c.id, 
                        (SELECT COUNT(*) FROM class_sessions WHERE course_id = c.id),
                        (SELECT COUNT(*) FROM attendance_logs al 
                         JOIN class_sessions cs ON al.session_id = cs.id 
                         WHERE cs.course_id = c.id)
                   FROM courses c
                   ON DUPLICATE KEY UPDATE 
                        total_sessions = VALUES(total_sessions),
                        total_attendances = VALUES(total_attendances)");

        // Trigger para nuevas sesiones
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_session_insert");
        $pdo->exec("CREATE TRIGGER trg_after_session_insert 
                   AFTER INSERT ON class_sessions
                   FOR EACH ROW 
                   BEGIN
                        INSERT INTO course_stats (course_id, total_sessions) 
                        VALUES (NEW.course_id, 1)
                        ON DUPLICATE KEY UPDATE total_sessions = total_sessions + 1;
                   END");

        // Trigger para eliminar sesiones
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_session_delete");
        $pdo->exec("CREATE TRIGGER trg_after_session_delete 
                   AFTER DELETE ON class_sessions
                   FOR EACH ROW 
                   BEGIN
                        UPDATE course_stats SET total_sessions = total_sessions - 1 
                        WHERE course_id = OLD.course_id;
                   END");

        // Trigger para nuevos registros de asistencia
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_attendance_insert");
        $pdo->exec("CREATE TRIGGER trg_after_attendance_insert 
                   AFTER INSERT ON attendance_logs
                   FOR EACH ROW 
                   BEGIN
                        DECLARE v_course_id INT;
                        SELECT course_id INTO v_course_id FROM class_sessions WHERE id = NEW.session_id;
                        
                        UPDATE course_stats SET total_attendances = total_attendances + 1 
                        WHERE course_id = v_course_id;
                   END");
                   
        // Trigger para eliminar registros de asistencia
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_attendance_delete");
        $pdo->exec("CREATE TRIGGER trg_after_attendance_delete 
                   AFTER DELETE ON attendance_logs
                   FOR EACH ROW 
                   BEGIN
                        DECLARE v_course_id INT;
                        SELECT course_id INTO v_course_id FROM class_sessions WHERE id = OLD.session_id;
                        
                        UPDATE course_stats SET total_attendances = total_attendances - 1 
                        WHERE course_id = v_course_id;
                   END");
    },
    'down' => function($pdo) {
        $pdo->exec("DROP TABLE IF EXISTS course_stats");
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_session_insert");
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_session_delete");
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_attendance_insert");
        $pdo->exec("DROP TRIGGER IF EXISTS trg_after_attendance_delete");
    }
];