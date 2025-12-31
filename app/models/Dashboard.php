<?php

class Dashboard {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getCounts() {
        $stats = [];

        // Count Students
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
        $stats['students'] = $this->db->single()->count;

        // Count Teachers
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'");
        $stats['teachers'] = $this->db->single()->count;

        // Count Active Courses (in active periods)
        $this->db->query("SELECT COUNT(*) as count FROM courses c 
                          JOIN academic_periods ap ON c.period_id = ap.id 
                          WHERE ap.is_active = 1");
        $stats['courses'] = $this->db->single()->count;

        return $stats;
    }

    public function getRecentAttendance($limit = 5) {
        // Get global recent attendance
        $sql = "SELECT al.scan_time, al.status, u.name as student_name, 
                       s.name as subject_name, r.name as room_name
                FROM attendance_logs al
                JOIN users u ON al.student_id = u.id
                JOIN class_sessions cs ON al.session_id = cs.id
                JOIN courses c ON cs.course_id = c.id
                JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN rooms r ON cs.room_id = r.id
                ORDER BY al.scan_time DESC
                LIMIT :limit";

        $this->db->query($sql);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getTeacherStats($teacherId) {
        $stats = [];

        // Total Courses
        $this->db->query("SELECT COUNT(*) as count FROM courses WHERE teacher_id = :tid");
        $this->db->bind(':tid', $teacherId);
        $stats['total_courses'] = $this->db->single()->count;

        // Total Unique Students
        $this->db->query("SELECT COUNT(DISTINCT ce.student_id) as count 
                          FROM course_enrollments ce 
                          JOIN courses c ON ce.course_id = c.id 
                          WHERE c.teacher_id = :tid");
        $this->db->bind(':tid', $teacherId);
        $stats['total_students'] = $this->db->single()->count;

        // Global Attendance Avg (%)
        $this->db->query("SELECT 
                            (COUNT(al.id) * 100.0 / NULLIF(SUM(expected.student_count), 0)) as avg_attendance
                          FROM courses c
                          JOIN class_sessions cs ON c.id = cs.course_id
                          LEFT JOIN attendance_logs al ON cs.id = al.session_id
                          JOIN (
                              SELECT course_id, COUNT(student_id) as student_count 
                              FROM course_enrollments 
                              GROUP BY course_id
                          ) as expected ON c.id = expected.course_id
                          WHERE c.teacher_id = :tid 
                          AND cs.specific_date <= CURDATE()");
        $this->db->bind(':tid', $teacherId);
        $row = $this->db->single();
        $stats['avg_attendance'] = ($row && $row->avg_attendance) ? round($row->avg_attendance, 1) : 0;

        return $stats;
    }

    public function getAtRiskStudents($teacherId, $threshold = 70) {
        $this->db->query("SELECT 
                            u.name as student_name, 
                            s.name as subject_name,
                            c.group_name,
                            COUNT(al.id) as attended_count,
                            (SELECT COUNT(*) FROM class_sessions cs2 WHERE cs2.course_id = c.id AND cs2.specific_date <= CURDATE()) as total_sessions,
                            (COUNT(al.id) * 100.0 / NULLIF((SELECT COUNT(*) FROM class_sessions cs2 WHERE cs2.course_id = c.id AND cs2.specific_date <= CURDATE()), 0)) as attendance_pct
                          FROM course_enrollments ce
                          JOIN users u ON ce.student_id = u.id
                          JOIN courses c ON ce.course_id = c.id
                          JOIN subjects s ON c.subject_id = s.id
                          LEFT JOIN class_sessions cs ON c.id = cs.course_id AND cs.specific_date <= CURDATE()
                          LEFT JOIN attendance_logs al ON cs.id = al.session_id AND al.student_id = u.id
                          WHERE c.teacher_id = :tid
                          GROUP BY u.id, c.id
                          HAVING attendance_pct < :threshold AND total_sessions > 0
                          ORDER BY attendance_pct ASC
                          LIMIT 5");
        $this->db->bind(':tid', $teacherId);
        $this->db->bind(':threshold', $threshold);
        return $this->db->resultSet();
    }
}