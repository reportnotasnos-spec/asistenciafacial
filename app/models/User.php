<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Register a new user and create their specific role profile.
     *
     * @param array $data Associative array containing name, email, password, and role
     * @return bool True if registration is successful, false otherwise
     */
    public function register($data)
    {
        $this->db->query('INSERT INTO users (name, email, password, role) VALUES(:name, :email, :password, :role)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

        // Execute
        if ($this->db->execute()) {
            $userId = $this->db->lastInsertId();
            
            if ($data['role'] == 'student') {
                $this->createStudentProfile($userId);
            } elseif ($data['role'] == 'teacher') {
                $this->createTeacherProfile($userId);
            }
            
            return true;
        } else {
            return false;
        }
    }

    public function createStudentProfile($userId)
    {
        $this->db->query('INSERT INTO student_details (user_id) VALUES (:user_id)');
        $this->db->bind(':user_id', $userId);
        $this->db->execute();
    }

    public function createTeacherProfile($userId)
    {
        $this->db->query('INSERT INTO teacher_details (user_id) VALUES (:user_id)');
        $this->db->bind(':user_id', $userId);
        $this->db->execute();
    }

    /**
     * Get Student Details
     */
    public function getStudentDetails($userId)
    {
        $this->db->query('SELECT * FROM student_details WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * Get Teacher Details
     */
    public function getTeacherDetails($userId)
    {
        $this->db->query('SELECT * FROM teacher_details WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * Update Student Details
     */
    public function updateStudentDetails($userId, $data)
    {
        // Check if details exist, if not create them
        if (!$this->getStudentDetails($userId)) {
            $this->createStudentProfile($userId);
        }

        $this->db->query('UPDATE student_details SET 
            student_id_number = :student_id_number,
            date_of_birth = :date_of_birth,
            grade_level = :grade_level,
            enrollment_date = :enrollment_date,
            emergency_contact_name = :emergency_contact_name,
            emergency_contact_phone = :emergency_contact_phone
            WHERE user_id = :user_id');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':student_id_number', $data['student_id_number']);
        $this->db->bind(':date_of_birth', empty($data['date_of_birth']) ? null : $data['date_of_birth']);
        $this->db->bind(':grade_level', $data['grade_level']);
        $this->db->bind(':enrollment_date', empty($data['enrollment_date']) ? null : $data['enrollment_date']);
        $this->db->bind(':emergency_contact_name', $data['emergency_contact_name']);
        $this->db->bind(':emergency_contact_phone', $data['emergency_contact_phone']);

        return $this->db->execute();
    }

    /**
     * Update Teacher Details
     */
    public function updateTeacherDetails($userId, $data)
    {
        // Check if details exist, if not create them
        if (!$this->getTeacherDetails($userId)) {
            $this->createTeacherProfile($userId);
        }

        $this->db->query('UPDATE teacher_details SET 
            employee_id_number = :employee_id_number,
            department = :department,
            specialization = :specialization,
            hire_date = :hire_date,
            office_location = :office_location,
            contact_phone = :contact_phone,
            bio = :bio
            WHERE user_id = :user_id');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':employee_id_number', $data['employee_id_number']);
        $this->db->bind(':department', $data['department']);
        $this->db->bind(':specialization', $data['specialization']);
        $this->db->bind(':hire_date', empty($data['hire_date']) ? null : $data['hire_date']);
        $this->db->bind(':office_location', $data['office_location']);
        $this->db->bind(':contact_phone', $data['contact_phone']);
        $this->db->bind(':bio', $data['bio']);

        return $this->db->execute();
    }

    /**
     * Find user by email
     */
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }
    
    /**
     * Authenticate a user by email and password.
     *
     * @param string $email User's email address
     * @param string $password User's plain text password
     * @return object|false Returns the user object on success, false on failure
     */
    public function login($email, $password)
    {
        $row = $this->findUserByEmail($email);

        if ($row === false) {
            return false;
        }

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Update user password
     */
    public function updatePassword($id, $password)
    {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $id);
        $this->db->bind(':password', $password);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update Profile Picture
     */
    public function updateProfilePicture($userId, $role, $imagePath)
    {
        $table = ($role == 'student') ? 'student_details' : 'teacher_details';
        
        // Ensure the record exists (just in case)
        if ($role == 'student' && !$this->getStudentDetails($userId)) {
            $this->createStudentProfile($userId);
        } elseif ($role == 'teacher' && !$this->getTeacherDetails($userId)) {
            $this->createTeacherProfile($userId);
        }

        $this->db->query("UPDATE $table SET profile_picture_url = :image_path WHERE user_id = :user_id");
        $this->db->bind(':image_path', $imagePath);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }
}
