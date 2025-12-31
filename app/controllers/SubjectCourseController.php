<?php

class SubjectCourseController extends Controller {
    private $model;
    private $academicModel;

    private $sessionModel;

    public function __construct() {
        if (!Session::isLoggedIn() || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        $this->model = $this->model('SubjectCourse');
        $this->academicModel = $this->model('Academic');
        $this->sessionModel = $this->model('ClassSession');
    }

    public function schedule($courseId) {
        $courses = $this->model->getCourses();
        $selectedCourse = null;
        foreach($courses as $c) {
            if($c->id == $courseId) $selectedCourse = $c;
        }

        if(!$selectedCourse) {
            header('location: ' . URL_ROOT . '/admin/subjectCourse');
            exit;
        }

        $data = [
            'course' => $selectedCourse,
            'rooms' => $this->academicModel->getRooms(),
            'existing_sessions' => $this->sessionModel->getSessionsByCourse($courseId)
        ];

        $this->view('admin/subjects_courses/schedule', $data);
    }

    public function generateSchedule($courseId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $schedules = [];
            $inputSchedules = $_POST['schedules'] ?? [];
            
            foreach($inputSchedules as $day => $details) {
                // Check if the day is enabled
                if(isset($details['enabled']) && $details['enabled'] == 1) {
                    // Validate basic required fields for that day
                    if(!empty($details['start_time']) && !empty($details['end_time'])) {
                        $schedules[] = [
                            'day_of_week' => $day,
                            'start_time' => $details['start_time'],
                            'end_time' => $details['end_time'],
                            'room_id' => $details['room_id']
                        ];
                    }
                }
            }

            if (empty($schedules)) {
                Session::flash('schedule_msg', __('msg_select_day'), 'alert alert-danger');
                header('location: ' . URL_ROOT . '/admin/subjectCourse/schedule/' . $courseId);
                exit;
            }

            $count = $this->sessionModel->generateSchedule($courseId, $_POST['period_id'], $schedules);
            
            $msg = ($count > 0) ? str_replace(':count', $count, __('msg_sessions_gen')) : __('msg_no_sessions');

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'count' => $count, 
                    'message' => $msg
                ]);
                exit;
            }

            if ($count > 0) {
                Session::flash('schedule_msg', $msg);
            } else {
                Session::flash('schedule_msg', $msg, 'alert alert-warning');
            }

            header('location: ' . URL_ROOT . '/admin/subjectCourse/schedule/' . $courseId);
        }
    }

    public function index() {
        $data = [
            'subjects' => $this->model->getSubjects(),
            'courses' => $this->model->getCourses(),
            'programs' => $this->academicModel->getPrograms(),
            'periods' => $this->academicModel->getPeriods(),
            'teachers' => $this->model->getTeachers()
        ];
        $this->view('admin/subjects_courses/index', $data);
    }

    // --- Subject CRUD ---
    public function addSubject() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'program_id' => $_POST['program_id'],
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code']),
                'credits' => $_POST['credits']
            ];
            
            $success = $this->model->addSubject($data);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success, 'message' => $success ? __('msg_subject_added') : __('msg_error')]);
                exit;
            }

            if ($success) {
                Session::flash('subject_msg', __('msg_subject_added'));
            }
            header('location: ' . URL_ROOT . '/admin/subjectCourse#subjects');
        }
    }

    public function editSubject() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'program_id' => $_POST['program_id'],
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code']),
                'credits' => $_POST['credits']
            ];
            if ($this->model->updateSubject($data)) {
                Session::flash('subject_msg', __('msg_subject_updated'));
            }
            header('location: ' . URL_ROOT . '/admin/subjectCourse#subjects');
        }
    }

    public function deleteSubject($id) {
        if ($this->model->deleteSubject($id)) {
            Session::flash('subject_msg', __('msg_subject_deleted'));
        } else {
            Session::flash('subject_msg', __('msg_dependency_err'), 'alert alert-danger');
        }
        header('location: ' . URL_ROOT . '/admin/subjectCourse#subjects');
    }

    // --- Course CRUD ---
    public function addCourse() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'subject_id' => $_POST['subject_id'],
                'period_id' => $_POST['period_id'],
                'teacher_id' => $_POST['teacher_id'],
                'group_name' => trim($_POST['group_name'])
            ];
            
            $success = $this->model->addCourse($data);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success, 'message' => $success ? __('msg_course_added') : __('msg_error')]);
                exit;
            }

            if ($success) {
                Session::flash('course_msg', __('msg_course_added'));
            }
            header('location: ' . URL_ROOT . '/admin/subjectCourse#courses');
        }
    }

    public function editCourse() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'subject_id' => $_POST['subject_id'],
                'period_id' => $_POST['period_id'],
                'teacher_id' => $_POST['teacher_id'],
                'group_name' => trim($_POST['group_name'])
            ];
            if ($this->model->updateCourse($data)) {
                Session::flash('course_msg', __('msg_course_updated'));
            }
            header('location: ' . URL_ROOT . '/admin/subjectCourse#courses');
        }
    }

    public function deleteCourse($id) {
        if ($this->model->deleteCourse($id)) {
            Session::flash('course_msg', __('msg_course_deleted'));
        }
        header('location: ' . URL_ROOT . '/admin/subjectCourse#courses');
    }

    // --- Session Management ---
    public function addSession() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'course_id' => $_POST['course_id'],
                'room_id' => $_POST['room_id'],
                'specific_date' => $_POST['specific_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time']
            ];

            $newId = $this->sessionModel->createSession($data);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => ($newId !== false), 'id' => $newId, 'message' => ($newId !== false) ? __('msg_session_added') : __('msg_error')]);
                exit;
            }

            if ($newId) {
                Session::flash('schedule_msg', __('msg_session_added'));
            } else {
                Session::flash('schedule_msg', __('msg_error'), 'alert alert-danger');
            }
            header('location: ' . URL_ROOT . '/admin/subjectCourse/schedule/' . $data['course_id']);
        }
    }

    public function updateSession() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'room_id' => $_POST['room_id'],
                'specific_date' => $_POST['specific_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time']
            ];

            $success = $this->sessionModel->updateSession($data);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success, 'message' => $success ? __('msg_session_updated') : __('msg_error')]);
                exit;
            }

            if ($success) {
                Session::flash('schedule_msg', __('msg_session_updated'));
            } else {
                Session::flash('schedule_msg', __('msg_error'), 'alert alert-danger');
            }
            header('location: ' . URL_ROOT . '/admin/subjectCourse/schedule/' . $_POST['course_id']);
        }
    }

    public function deleteSession($sessionId) {
        $session = $this->sessionModel->getSessionById($sessionId);
        if (!$session) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Session not found']);
                exit;
            }
            header('location: ' . URL_ROOT . '/admin/subjectCourse');
            exit;
        }
        
        $success = $this->sessionModel->deleteSession($sessionId);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $success ? __('msg_session_deleted') : __('msg_error')]);
            exit;
        }

        if ($success) {
            Session::flash('schedule_msg', __('msg_session_deleted'));
        } else {
            Session::flash('schedule_msg', __('msg_error'), 'alert alert-danger');
        }
        header('location: ' . URL_ROOT . '/admin/subjectCourse/schedule/' . $session->course_id);
    }

    public function getSessionsDataTable($courseId) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            try {
                // Clear any previous output buffers
                if (ob_get_level()) ob_clean();

                $draw = intval($_GET['draw'] ?? 1);
                $start = intval($_GET['start'] ?? 0);
                $length = intval($_GET['length'] ?? 10);
                $search = $_GET['search']['value'] ?? '';
                
                // Order details
                $columnIndex = $_GET['order'][0]['column'] ?? 0;
                $columnName = $_GET['columns'][$columnIndex]['data'] ?? 'specific_date';
                $columnSortOrder = $_GET['order'][0]['dir'] ?? 'asc';

                $data = $this->sessionModel->getSessionsPaginated($courseId, $start, $length, $search, $columnName, $columnSortOrder);
                
                header('Content-Type: application/json');
                echo json_encode([
                    "draw" => $draw,
                    "recordsTotal" => $data['total'],
                    "recordsFiltered" => $data['filtered'],
                    "data" => $data['rows']
                ]);
            } catch (\Throwable $e) {
                header('Content-Type: application/json', true, 500);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine()
                ]);
            }
            exit;
        }
    }
}