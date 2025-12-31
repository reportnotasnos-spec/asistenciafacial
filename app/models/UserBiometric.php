<?php

class UserBiometric
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function register($userId, $biometricData)
    {
        // First, deactivate any existing active biometrics for this user (optional policy, but good for single-face systems)
        $this->deactivateOldBiometrics($userId);

        $this->db->query('INSERT INTO user_biometrics (user_id, biometric_data, biometric_type) VALUES (:user_id, :biometric_data, :biometric_type)');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':biometric_data', $biometricData);
        $this->db->bind(':biometric_type', 'face_encoding');

        return $this->db->execute();
    }

    public function getActiveBiometric($userId)
    {
        $this->db->query('SELECT * FROM user_biometrics WHERE user_id = :user_id AND is_active = 1 LIMIT 1');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function hasBiometric($userId)
    {
        $this->db->query('SELECT id FROM user_biometrics WHERE user_id = :user_id AND is_active = 1 LIMIT 1');
        $this->db->bind(':user_id', $userId);
        return $this->db->single() ? true : false;
    }

    private function deactivateOldBiometrics($userId)
    {
        $this->db->query('UPDATE user_biometrics SET is_active = 0 WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $this->db->execute();
    }
}
