<?php

class Course {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all courses assigned to a specific teacher for the active period.
     */
    public function getCoursesByTeacher($teacherId) {
        $this->db->query("SELECT c.*, s.name as subject_name, s.code as subject_code, ap.name as period_name,
                          (SELECT COUNT(*) FROM course_enrollments ce WHERE ce.course_id = c.id) as student_count,
                          (
                              SELECT (COUNT(al.id) * 100.0 / NULLIF(COUNT(DISTINCT cs.id) * (SELECT COUNT(*) FROM course_enrollments ce2 WHERE ce2.course_id = c.id), 0))
                              FROM class_sessions cs
                              LEFT JOIN attendance_logs al ON cs.id = al.session_id
                              WHERE cs.course_id = c.id AND cs.specific_date <= CURDATE()
                          ) as attendance_avg
                          FROM courses c
                          JOIN subjects s ON c.subject_id = s.id
                          JOIN academic_periods ap ON c.period_id = ap.id
                          WHERE c.teacher_id = :teacher_id
                          ORDER BY s.name ASC");
        $this->db->bind(':teacher_id', $teacherId);
        return $this->db->resultSet();
    }

    public function getCourseById($id) {
        $this->db->query("SELECT c.*, s.name as subject_name, s.code as subject_code, 
                                 u.name as teacher_name, ap.name as period_name
                          FROM courses c
                          JOIN subjects s ON c.subject_id = s.id
                          JOIN users u ON c.teacher_id = u.id
                          JOIN academic_periods ap ON c.period_id = ap.id
                          WHERE c.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Get all students enrolled in a course, including their biometric data.
     */
    public function getEnrolledStudentsWithBiometrics($courseId) {
        $sql = "SELECT u.id, u.name, u.email, ub.biometric_data as descriptor, 
                       sd.student_id_number as student_code, sd.profile_picture_url as profile_picture
                FROM course_enrollments ce
                JOIN users u ON ce.student_id = u.id
                LEFT JOIN user_biometrics ub ON u.id = ub.user_id
                LEFT JOIN student_details sd ON u.id = sd.user_id
                WHERE ce.course_id = :course_id
                ORDER BY u.name ASC";

        $this->db->query($sql);
        $this->db->bind(':course_id', $courseId);
        return $this->db->resultSet();
    }
}
