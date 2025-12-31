<?php

class SystemSetting {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAll() {
        $this->db->query('SELECT * FROM system_settings ORDER BY setting_group, setting_key');
        return $this->db->resultSet();
    }

    public function getByKey($key) {
        $this->db->query('SELECT setting_value FROM system_settings WHERE setting_key = :key');
        $this->db->bind(':key', $key);
        $row = $this->db->single();
        return $row ? $row->setting_value : null;
    }

    public function update($key, $value) {
        $this->db->query('UPDATE system_settings SET setting_value = :value WHERE setting_key = :key');
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);
        return $this->db->execute();
    }

    public function updateMultiple($settings) {
        $success = true;
        foreach ($settings as $key => $value) {
            if (!$this->update($key, $value)) {
                $success = false;
            }
        }
        return $success;
    }
}
