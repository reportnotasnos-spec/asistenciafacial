<?php

class SubjectCourse {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // --- Subjects ---
    public function getSubjects() {
        $this->db->query("SELECT s.*, sp.name as program_name 
                          FROM subjects s 
                          JOIN study_programs sp ON s.program_id = sp.id 
                          ORDER BY sp.name ASC, s.name ASC");
        return $this->db->resultSet();
    }

    public function addSubject($data) {
        $this->db->query("INSERT INTO subjects (program_id, name, code, credits) VALUES (:program_id, :name, :code, :credits)");
        $this->db->bind(':program_id', $data['program_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':credits', $data['credits']);
        return $this->db->execute();
    }

    public function updateSubject($data) {
        $this->db->query("UPDATE subjects SET program_id = :program_id, name = :name, code = :code, credits = :credits WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':program_id', $data['program_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':credits', $data['credits']);
        return $this->db->execute();
    }

    public function deleteSubject($id) {
        $this->db->query("DELETE FROM subjects WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- Courses ---
    public function getCourses() {
        $this->db->query("SELECT c.*, s.name as subject_name, ap.name as period_name, u.name as teacher_name 
                          FROM courses c 
                          JOIN subjects s ON c.subject_id = s.id 
                          JOIN academic_periods ap ON c.period_id = ap.id 
                          JOIN users u ON c.teacher_id = u.id 
                          ORDER BY ap.start_date DESC, s.name ASC");
        return $this->db->resultSet();
    }

    public function addCourse($data) {
        $this->db->query("INSERT INTO courses (subject_id, period_id, teacher_id, group_name) VALUES (:subject_id, :period_id, :teacher_id, :group_name)");
        $this->db->bind(':subject_id', $data['subject_id']);
        $this->db->bind(':period_id', $data['period_id']);
        $this->db->bind(':teacher_id', $data['teacher_id']);
        $this->db->bind(':group_name', $data['group_name']);
        return $this->db->execute();
    }

    public function updateCourse($data) {
        $this->db->query("UPDATE courses SET subject_id = :subject_id, period_id = :period_id, teacher_id = :teacher_id, group_name = :group_name WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':subject_id', $data['subject_id']);
        $this->db->bind(':period_id', $data['period_id']);
        $this->db->bind(':teacher_id', $data['teacher_id']);
        $this->db->bind(':group_name', $data['group_name']);
        return $this->db->execute();
    }

    public function deleteCourse($id) {
        $this->db->query("DELETE FROM courses WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getTeachers() {
        $this->db->query("SELECT id, name FROM users WHERE role = 'teacher' ORDER BY name ASC");
        return $this->db->resultSet();
    }
}
