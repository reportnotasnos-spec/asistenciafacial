<?php

class Academic {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // --- Study Programs ---
    public function getPrograms() {
        $this->db->query("SELECT * FROM study_programs ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getProgramById($id) {
        $this->db->query("SELECT * FROM study_programs WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addProgram($data) {
        $this->db->query("INSERT INTO study_programs (name, code) VALUES (:name, :code)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':code', $data['code']);
        return $this->db->execute();
    }

    public function updateProgram($data) {
        $this->db->query("UPDATE study_programs SET name = :name, code = :code WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':code', $data['code']);
        return $this->db->execute();
    }

    public function deleteProgram($id) {
        $this->db->query("DELETE FROM study_programs WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- Academic Periods ---
    public function getPeriods() {
        $this->db->query("SELECT * FROM academic_periods ORDER BY start_date DESC");
        return $this->db->resultSet();
    }

    public function getPeriodById($id) {
        $this->db->query("SELECT * FROM academic_periods WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addPeriod($data) {
        $this->db->query("INSERT INTO academic_periods (name, start_date, end_date, is_active) VALUES (:name, :start_date, :end_date, :is_active)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    public function updatePeriod($data) {
        $this->db->query("UPDATE academic_periods SET name = :name, start_date = :start_date, end_date = :end_date, is_active = :is_active WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    public function deletePeriod($id) {
        $this->db->query("DELETE FROM academic_periods WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function setActivePeriod($id) {
        // Set all to 0
        $this->db->query("UPDATE academic_periods SET is_active = 0");
        $this->db->execute();
        // Set specific to 1
        $this->db->query("UPDATE academic_periods SET is_active = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- Rooms ---
    public function getRooms() {
        $this->db->query("SELECT * FROM rooms ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getRoomById($id) {
        $this->db->query("SELECT * FROM rooms WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addRoom($data) {
        $this->db->query("INSERT INTO rooms (name, capacity, location) VALUES (:name, :capacity, :location)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':location', $data['location']);
        return $this->db->execute();
    }

    public function updateRoom($data) {
        $this->db->query("UPDATE rooms SET name = :name, capacity = :capacity, location = :location WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':location', $data['location']);
        return $this->db->execute();
    }

    public function deleteRoom($id) {
        $this->db->query("DELETE FROM rooms WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
