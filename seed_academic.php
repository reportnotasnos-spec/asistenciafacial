<?php
require_once 'config/app.php';
require_once 'config/database.php';
require_once 'app/core/Database.php';
require_once 'app/core/Autoloader.php';

// Mocking Session for Database class if needed (it doesn't use it but just in case)
spl_autoload_register(function ($class) {
    $path = 'app/core/' . $class . '.php';
    if (file_exists($path)) require $path;
    $path = 'app/models/' . $class . '.php';
    if (file_exists($path)) require $path;
});

$db = new Database();

echo "Starting seeding process...\n";

try {
    // 1. Clear existing data (in reverse order of dependencies)
    echo "Cleaning old data...\n";
    $db->query("SET FOREIGN_KEY_CHECKS = 0");
    $db->execute();
    $db->query("TRUNCATE attendance_logs"); $db->execute();
    $db->query("TRUNCATE class_sessions"); $db->execute();
    $db->query("TRUNCATE course_enrollments"); $db->execute();
    $db->query("TRUNCATE courses"); $db->execute();
    $db->query("TRUNCATE subjects"); $db->execute();
    $db->query("TRUNCATE rooms"); $db->execute();
    $db->query("TRUNCATE academic_periods"); $db->execute();
    $db->query("TRUNCATE study_programs"); $db->execute();
    $db->query("SET FOREIGN_KEY_CHECKS = 1");
    $db->execute();

    // 2. Create Study Program
    echo "Creating Study Program...\n";
    $db->query("INSERT INTO study_programs (name, code) VALUES ('Software Engineering', 'SE-101')");
    $db->execute();
    $programId = $db->lastInsertId();

    // 3. Create Academic Period
    echo "Creating Academic Period...\n";
    $db->query("INSERT INTO academic_periods (name, start_date, end_date, is_active) VALUES ('2025-I', '2025-01-01', '2025-06-30', 1)");
    $db->execute();
    $periodId = $db->lastInsertId();

    // 4. Create Room
    echo "Creating Room...\n";
    $db->query("INSERT INTO rooms (name, capacity, location) VALUES ('Lab 302', 30, 'Building C - 3rd Floor')");
    $db->execute();
    $roomId = $db->lastInsertId();

    // 5. Create Subject
    echo "Creating Subject...\n";
    $db->query("INSERT INTO subjects (program_id, name, code, credits) VALUES (:pid, 'Web Development II', 'WD2', 4)");
    $db->bind(':pid', $programId);
    $db->execute();
    $subjectId = $db->lastInsertId();

    // 6. Get/Create Teacher (Santiago)
    $db->query("SELECT id FROM users WHERE email = 'teacher@test.com'");
    $teacher = $db->single();
    if (!$teacher) {
        echo "Creating Teacher (teacher@test.com)...\n";
        $password = password_hash('123456', PASSWORD_DEFAULT);
        $db->query("INSERT INTO users (name, email, password, role) VALUES ('Santiago Teacher', 'teacher@test.com', :pw, 'teacher')");
        $db->bind(':pw', $password);
        $db->execute();
        $teacherId = $db->lastInsertId();

        // Create teacher details
        $db->query("INSERT INTO teacher_details (user_id, employee_id_number, department) VALUES (:uid, :emp_id, 'Engineering')");
        $db->bind(':uid', $teacherId);
        $db->bind(':emp_id', 'EMP-' . rand(1000, 9999));
        $db->execute();
    } else {
        $teacherId = $teacher->id;
        echo "Using existing teacher: teacher@test.com (ID: $teacherId)\n";
    }

    // 7. Create Course
    echo "Creating Course...\n";
    $db->query("INSERT INTO courses (subject_id, period_id, teacher_id, group_name) VALUES (:sid, :pid, :tid, 'Group A')");
    $db->bind(':sid', $subjectId);
    $db->bind(':pid', $periodId);
    $db->bind(':tid', $teacherId);
    $db->execute();
    $courseId = $db->lastInsertId();

    // 8. Create/Link Students
    echo "Ensuring Students exist and enrolling them...\n";
    $students = [
        ['name' => 'Alice Student', 'email' => 'alice@test.com'],
        ['name' => 'Bob Student', 'email' => 'bob@test.com'],
        ['name' => 'Charlie Student', 'email' => 'charlie@test.com']
    ];

    foreach ($students as $s) {
        $db->query("SELECT id FROM users WHERE email = :email");
        $db->bind(':email', $s['email']);
        $user = $db->single();
        
        if (!$user) {
            $password = password_hash('123456', PASSWORD_DEFAULT);
            $db->query("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :pw, 'student')");
            $db->bind(':name', $s['name']);
            $db->bind(':email', $s['email']);
            $db->bind(':pw', $password);
            $db->execute();
            $studentId = $db->lastInsertId();
            
            // Add student details
            $db->query("INSERT INTO student_details (user_id, student_id_number) VALUES (:uid, :sid)");
            $db->bind(':uid', $studentId);
            $db->bind(':sid', 'STU-' . rand(1000, 9999));
            $db->execute();
        } else {
            $studentId = $user->id;
        }

        // Enroll in course
        $db->query("INSERT IGNORE INTO course_enrollments (course_id, student_id) VALUES (:cid, :sid)");
        $db->bind(':cid', $courseId);
        $db->bind(':sid', $studentId);
        $db->execute();
    }

    // 9. Generate Sessions
    echo "Generating Sessions...\n";
    $sessionModel = new ClassSession();
    // Schedule for Monday and Wednesday
    $schedules = [
        ['day_of_week' => 'Monday', 'start_time' => '08:00:00', 'end_time' => '10:00:00', 'room_id' => $roomId],
        ['day_of_week' => 'Wednesday', 'start_time' => '08:00:00', 'end_time' => '10:00:00', 'room_id' => $roomId],
        ['day_of_week' => 'Sunday', 'start_time' => '00:00:00', 'end_time' => '23:59:59', 'room_id' => $roomId] // For testing today
    ];
    $count = $sessionModel->generateSchedule($courseId, $periodId, $schedules);
    echo "Generated $count class sessions.\n";

    echo "Seeding completed successfully!\n";
    echo "Credentials:\n";
    echo "Teacher: teacher@test.com / 123456\n";
    echo "Students: alice@test.com, bob@test.com / 123456\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
