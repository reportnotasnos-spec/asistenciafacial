<?php

class AttendanceService {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Calcula las estadísticas globales de un estudiante.
     */
    public function getGlobalStudentStats($studentId) {
        $stats = [];

        // Total Courses
        $this->db->query("SELECT COUNT(*) as count FROM course_enrollments WHERE student_id = :sid");
        $this->db->bind(':sid', $studentId);
        $stats['total_courses'] = $this->db->single()->count;

        // Global Attendance (%)
        $this->db->query("SELECT 
                            (COUNT(al.id) * 100.0 / NULLIF(COUNT(DISTINCT cs.id), 0)) as avg_attendance
                          FROM course_enrollments ce
                          JOIN class_sessions cs ON ce.course_id = cs.course_id
                          LEFT JOIN attendance_logs al ON cs.id = al.session_id AND al.student_id = ce.student_id
                          WHERE ce.student_id = :sid 
                          AND cs.specific_date <= CURDATE()");
        $this->db->bind(':sid', $studentId);
        $row = $this->db->single();
        $stats['avg_attendance'] = ($row && $row->avg_attendance) ? round($row->avg_attendance, 1) : 0;

        return $stats;
    }

    /**
     * Obtiene el detalle de asistencia por curso para un estudiante.
     */
    public function getStudentCoursesAttendanceDetails($studentId) {
        $this->db->query("SELECT 
                            c.id as course_id, 
                            s.name as subject_name, 
                            s.code as subject_code,
                            c.group_name,
                            u.name as teacher_name,
                            (SELECT COUNT(*) FROM class_sessions cs2 WHERE cs2.course_id = c.id AND cs2.specific_date <= CURDATE()) as total_sessions,
                            (SELECT COUNT(*) FROM attendance_logs al2 
                             JOIN class_sessions cs3 ON al2.session_id = cs3.id 
                             WHERE cs3.course_id = c.id AND al2.student_id = :sid AND cs3.specific_date <= CURDATE()) as attended_sessions
                          FROM course_enrollments ce
                          JOIN courses c ON ce.course_id = c.id
                          JOIN subjects s ON c.subject_id = s.id
                          JOIN users u ON c.teacher_id = u.id
                          WHERE ce.student_id = :sid
                          ORDER BY s.name ASC");
        
        $this->db->bind(':sid', $studentId);
        return $this->db->resultSet();
    }

    /**
     * Calcula las estadísticas de asistencia para un estudiante en un curso específico.
     */
    public function getStudentStatsInCourse($studentId, $courseId) {
        $this->db->query("SELECT 
                            (SELECT COUNT(*) FROM class_sessions WHERE course_id = :cid AND specific_date <= CURDATE()) as total_sessions,
                            (SELECT COUNT(*) FROM attendance_logs al 
                             JOIN class_sessions cs ON al.session_id = cs.id 
                             WHERE al.student_id = :sid AND cs.course_id = :cid AND cs.specific_date <= CURDATE()) as attended_sessions");
        
        $this->db->bind(':sid', $studentId);
        $this->db->bind(':cid', $courseId);
        
        $stats = $this->db->single();
        
        $total = $stats->total_sessions;
        $attended = $stats->attended_sessions;
        $percentage = ($total > 0) ? round(($attended * 100) / $total, 2) : 100;

        return [
            'total_sessions' => $total,
            'attended_sessions' => $attended,
            'absences' => $total - $attended,
            'attendance_percentage' => $percentage
        ];
    }

    /**
     * Obtiene el resumen de asistencia de un curso (para dashboards de profesores).
     */
    public function getCourseSummary($courseId) {
        $this->db->query("SELECT 
                            COUNT(DISTINCT ce.student_id) as enrolled_students,
                            (SELECT COUNT(*) FROM class_sessions WHERE course_id = :cid) as total_scheduled
                          FROM course_enrollments ce
                          WHERE ce.course_id = :cid");
        $this->db->bind(':cid', $courseId);
        $courseData = $this->db->single();

        $this->db->query("SELECT COUNT(*) as total_logs
                          FROM attendance_logs al
                          JOIN class_sessions cs ON al.session_id = cs.id
                          WHERE cs.course_id = :cid");
        $this->db->bind(':cid', $courseId);
        $logsData = $this->db->single();

        $totalPossible = $courseData->enrolled_students * $courseData->total_scheduled;
        $globalAttendance = ($totalPossible > 0) ? round(($logsData->total_logs * 100) / $totalPossible, 2) : 0;

        return [
            'enrolled_count' => $courseData->enrolled_students,
            'sessions_count' => $courseData->total_scheduled,
            'global_attendance_percentage' => $globalAttendance
        ];
    }
}