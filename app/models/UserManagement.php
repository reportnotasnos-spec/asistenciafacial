<?php

class UserManagement {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // --- Students ---
    public function getStudents() {
        $this->db->query("SELECT u.*, sd.student_id_number, sd.grade_level 
                          FROM users u 
                          LEFT JOIN student_details sd ON u.id = sd.user_id 
                          WHERE u.role = 'student' 
                          ORDER BY u.name ASC");
        return $this->db->resultSet();
    }

    // --- Teachers ---
    public function getTeachers() {
        $this->db->query("SELECT u.*, td.employee_id_number, td.department 
                          FROM users u 
                          LEFT JOIN teacher_details td ON u.id = td.user_id 
                          WHERE u.role = 'teacher' 
                          ORDER BY u.name ASC");
        return $this->db->resultSet();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUserBasic($data) {
        $this->db->query("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        return $this->db->execute();
    }

    public function updatePassword($id, $hashedPassword) {
        $this->db->query("UPDATE users SET password = :password WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':password', $hashedPassword);
        return $this->db->execute();
    }

    public function deleteUser($id) {
        // Foreign keys will handle deleting records in student_details/teacher_details/biometrics
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
