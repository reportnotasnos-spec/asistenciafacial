<?php

class Enrollment {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getEnrolledStudents($courseId) {
        $this->db->query("SELECT u.*, sd.student_id_number 
                          FROM course_enrollments ce
                          JOIN users u ON ce.student_id = u.id
                          JOIN student_details sd ON u.id = sd.user_id
                          WHERE ce.course_id = :course_id
                          ORDER BY u.name ASC");
        $this->db->bind(':course_id', $courseId);
        return $this->db->resultSet();
    }

    public function getAvailableStudents($courseId) {
        // Students NOT enrolled in this specific course
        $this->db->query("SELECT u.*, sd.student_id_number 
                          FROM users u
                          JOIN student_details sd ON u.id = sd.user_id
                          WHERE u.role = 'student' 
                          AND u.id NOT IN (SELECT student_id FROM course_enrollments WHERE course_id = :course_id)
                          ORDER BY u.name ASC");
        $this->db->bind(':course_id', $courseId);
        return $this->db->resultSet();
    }

    public function enroll($courseId, $studentId) {
        $this->db->query("INSERT IGNORE INTO course_enrollments (course_id, student_id) VALUES (:course_id, :student_id)");
        $this->db->bind(':course_id', $courseId);
        $this->db->bind(':student_id', $studentId);
        return $this->db->execute();
    }

    public function unenroll($courseId, $studentId) {
        $this->db->query("DELETE FROM course_enrollments WHERE course_id = :course_id AND student_id = :student_id");
        $this->db->bind(':course_id', $courseId);
        $this->db->bind(':student_id', $studentId);
        return $this->db->execute();
    }

    public function getStudentStats($studentId) {
        $stats = [];

        // Total Courses
        $this->db->query("SELECT COUNT(*) as count FROM course_enrollments WHERE student_id = :sid");
        $this->db->bind(':sid', $studentId);
        $stats['total_courses'] = $this->db->single()->count;

        // Global Attendance (%)
        // (Attended Sessions / Total Past Sessions across all courses)
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

    public function getStudentCoursesDetail($studentId) {
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
}