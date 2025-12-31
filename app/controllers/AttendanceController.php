<?php

class AttendanceController extends Controller {
    private $courseModel;
    private $sessionModel;

    public function __construct() {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        // Only Teachers and Admins can take attendance
        if ($_SESSION['user_role'] == 'student') {
            header('location: ' . URL_ROOT . '/profile');
            exit;
        }

        $this->courseModel = $this->model('Course');
        $this->sessionModel = $this->model('ClassSession');
    }

    public function index() {
        $dashboardModel = $this->model('Dashboard');
        
        // Teacher Dashboard Data
        $courses = $this->courseModel->getCoursesByTeacher($_SESSION['user_id']);
        $stats = $dashboardModel->getTeacherStats($_SESSION['user_id']);
        $todaysSession = $this->sessionModel->getTodaysSessionForTeacher($_SESSION['user_id']);
        $atRiskStudents = $dashboardModel->getAtRiskStudents($_SESSION['user_id']);
        $weeklySessions = $this->sessionModel->getSessionsForWeek($_SESSION['user_id']);

        // Group weekly sessions by date
        $schedule = [];
        foreach ($weeklySessions as $session) {
            $date = $session->specific_date;
            if (!isset($schedule[$date])) {
                $schedule[$date] = [];
            }
            $schedule[$date][] = $session;
        }
        
        $data = [
            'title' => 'Teacher Dashboard',
            'courses' => $courses,
            'stats' => $stats,
            'today' => $todaysSession,
            'atRisk' => $atRiskStudents,
            'schedule' => $schedule
        ];

        $this->view('attendance/index', $data);
    }

    public function course($courseId) {
        $course = $this->courseModel->getCourseById($courseId);
        
        // Security check: Ensure teacher owns this course
        if ($course->teacher_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin') {
            header('location: ' . URL_ROOT . '/attendance');
            exit;
        }

        $sessions = $this->sessionModel->getSessionsByCourse($courseId);

        $data = [
            'title' => 'Sessions for ' . $course->subject_name,
            'course' => $course,
            'sessions' => $sessions
        ];

        $this->view('attendance/course', $data);
    }

    public function session($sessionId) {
        $session = $this->sessionModel->getSessionById($sessionId);

        if (!$session) {
            header('location: ' . URL_ROOT . '/attendance');
            exit;
        }
        
        // Security check usually here too, but skipping for brevity/prototyping speed unless critical
        // Get enrolled students (with descriptors)
        $students = $this->courseModel->getEnrolledStudentsWithBiometrics($session->course_id);

        // Get current attendance log
        $attendanceLog = $this->sessionModel->getAttendanceLog($sessionId);

        $data = [
            'title' => 'Take Attendance',
            'session' => $session,
            'students' => $students,
            'attendance_log' => $attendanceLog
        ];

        $this->view('attendance/session', $data);
    }

    // API Endpoint
    public function mark() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $sessionId = $data['session_id'] ?? null;
            $studentId = $data['student_id'] ?? null;

            if ($sessionId && $studentId) {
                if ($this->sessionModel->markAttendance($sessionId, $studentId)) {
                    echo json_encode(['status' => 'success', 'message' => 'Attendance marked']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Already marked or error']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            }
        }
    }

    public function export($courseId) {
        $course = $this->courseModel->getCourseById($courseId);
        
        // Security Check
        if (!$course || ($course->teacher_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin')) {
             Session::flash('msg', 'Unauthorized access', 'alert alert-danger');
             header('location: ' . URL_ROOT . '/attendance');
             exit;
        }

        $sessions = $this->sessionModel->getSessionsByCourse($courseId);
        $students = $this->courseModel->getEnrolledStudentsWithBiometrics($courseId);
        $logs = $this->sessionModel->getAllCourseAttendanceLogs($courseId);

        // Process Logs into a Lookup Array
        $attendanceMap = [];
        foreach ($logs as $log) {
            $attendanceMap[$log->student_id][$log->session_id] = $log->status;
        }

        // Prepare CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Attendance_' . preg_replace('/[^a-zA-Z0-9]/', '_', $course->subject_name) . '_' . date('Y-m-d') . '.csv');
        $output = fopen('php://output', 'w');

        // Header Row
        $header = ['Student Name', 'Student ID'];
        $sessionIds = [];
        foreach ($sessions as $s) {
            $header[] = $s->specific_date . ' (' . substr($s->start_time, 0, 5) . ')';
            $sessionIds[] = $s->id;
        }
        $header[] = 'Total Present';
        $header[] = 'Total Sessions'; 
        $header[] = '% Attendance';
        
        fputcsv($output, $header);

        // Data Rows
        foreach ($students as $student) {
            $row = [$student->name, $student->student_code ?? 'N/A'];
            $presentCount = 0;
            $passedSessions = 0;
            
            foreach ($sessionIds as $sid) {
                // Find session object
                $sessionObj = null;
                foreach($sessions as $s) { if($s->id == $sid) { $sessionObj = $s; break; } }
                
                $status = $attendanceMap[$student->id][$sid] ?? null;
                
                $isPast = ($sessionObj->specific_date < date('Y-m-d')) || ($sessionObj->specific_date == date('Y-m-d') && $sessionObj->end_time < date('H:i:s'));
                
                if ($isPast) {
                    $passedSessions++;
                    if ($status == 'present' || $status == 'late') {
                        $presentCount++;
                        $row[] = ($status == 'present') ? 'P' : 'L';
                    } else {
                        $row[] = 'A';
                    }
                } else {
                    $row[] = '-'; // Future
                }
            }
            
            $percentage = ($passedSessions > 0) ? round(($presentCount / $passedSessions) * 100, 1) . '%' : 'N/A';
            $row[] = $presentCount;
            $row[] = $passedSessions;
            $row[] = $percentage;

            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
