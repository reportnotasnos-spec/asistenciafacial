<?php

class ProfileController extends Controller
{
    private $userModel;
    private $attendanceService;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        $this->userModel = $this->model('User');
        $this->attendanceService = new AttendanceService();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        $userDetails = null;
        $studentStats = null;
        $todaySchedule = [];
        $coursesDetail = [];
        $notifications = [];
        $hasBiometric = false;

        if ($userRole == 'student') {
            $userDetails = $this->userModel->getStudentDetails($userId);
            
            $biometricModel = $this->model('UserBiometric');
            $sessionModel = $this->model('ClassSession');
            $notificationModel = $this->model('Notification');
            
            $studentStats = $this->attendanceService->getGlobalStudentStats($userId);
            $hasBiometric = $biometricModel->hasBiometric($userId);
            $todaySchedule = $sessionModel->getStudentSessionsByDate($userId, date('Y-m-d'));
            $coursesDetail = $this->attendanceService->getStudentCoursesAttendanceDetails($userId);
            $notifications = $notificationModel->getAll($userId, 5);

        } elseif ($userRole == 'teacher') {
            $userDetails = $this->userModel->getTeacherDetails($userId);
        }

        $data = [
            'user' => [
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $userRole
            ],
            'details' => $userDetails,
            'student_stats' => $studentStats,
            'has_biometric' => $hasBiometric,
            'today_schedule' => $todaySchedule,
            'courses_detail' => $coursesDetail,
            'notifications' => $notifications
        ];

        $this->view('profile/index', $data);
    }

    public function attendance($courseId)
    {
        if ($_SESSION['user_role'] != 'student') {
            header('location: ' . URL_ROOT . '/profile');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $sessionModel = $this->model('ClassSession');
        $courseModel = $this->model('Course');

        $course = $courseModel->getCourseById($courseId);
        $history = $sessionModel->getStudentAttendanceByCourse($userId, $courseId);

        $data = [
            'course' => $course,
            'history' => $history
        ];

        $this->view('profile/attendance', $data);
    }

    public function edit()
    {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        $userDetails = null;

        if ($userRole == 'student') {
            $userDetails = $this->userModel->getStudentDetails($userId);
        } elseif ($userRole == 'teacher') {
            $userDetails = $this->userModel->getTeacherDetails($userId);
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             
             if ($userRole == 'student') {
                 $updateData = [
                     'student_id_number' => trim($_POST['student_id_number']),
                     'date_of_birth' => trim($_POST['date_of_birth']),
                     'grade_level' => trim($_POST['grade_level']),
                     'enrollment_date' => trim($_POST['enrollment_date']),
                     'emergency_contact_name' => trim($_POST['emergency_contact_name']),
                     'emergency_contact_phone' => trim($_POST['emergency_contact_phone'])
                 ];
                 
                 if ($this->userModel->updateStudentDetails($userId, $updateData)) {
                     Session::flash('profile_success', __('msg_user_updated'));
                     header('location: ' . URL_ROOT . '/profile');
                 } else {
                     die('Something went wrong');
                 }
                 
             } elseif ($userRole == 'teacher') {
                 $updateData = [
                     'employee_id_number' => trim($_POST['employee_id_number']),
                     'department' => trim($_POST['department']),
                     'specialization' => trim($_POST['specialization']),
                     'hire_date' => trim($_POST['hire_date']),
                     'office_location' => trim($_POST['office_location']),
                     'contact_phone' => trim($_POST['contact_phone']),
                     'bio' => trim($_POST['bio'])
                 ];
                 
                 if ($this->userModel->updateTeacherDetails($userId, $updateData)) {
                     Session::flash('profile_success', __('msg_user_updated'));
                     header('location: ' . URL_ROOT . '/profile');
                 } else {
                     die('Something went wrong');
                 }
             } else {
                 // Admin or other
                  header('location: ' . URL_ROOT . '/profile');
             }
             
        } else {
            $data = [
                'user' => [
                    'name' => $_SESSION['user_name'],
                    'email' => $_SESSION['user_email'],
                    'role' => $userRole
                ],
                'details' => $userDetails
            ];

            $this->view('profile/edit', $data);
        }
    }
}
