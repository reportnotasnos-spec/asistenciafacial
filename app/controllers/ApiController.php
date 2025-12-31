<?php

class ApiController extends Controller {
    private $attendanceService;
    private $userModel;
    private $sessionModel;

    public function __construct() {
        // En una API real, aquí usaríamos autenticación por Token (JWT/Bearer)
        // Por ahora mantendremos compatibilidad con la sesión actual
        if (!Session::isLoggedIn()) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $this->attendanceService = new AttendanceService();
        $this->userModel = $this->model('User');
        $this->sessionModel = $this->model('ClassSession');
    }

    /**
     * Endpoint para obtener estadísticas de un estudiante.
     * GET /api/stats/student/{id}
     */
    public function studentStats($studentId) {
        // Validar permisos (solo admin o el propio estudiante)
        if ($_SESSION['user_role'] != 'admin' && $_SESSION['user_id'] != $studentId) {
            $this->jsonResponse(['error' => 'Forbidden'], 403);
        }

        $stats = $this->attendanceService->getStudentCoursesAttendanceDetails($studentId);
        $global = $this->attendanceService->getGlobalStudentStats($studentId);

        $this->jsonResponse([
            'global' => $global,
            'courses' => $stats
        ]);
    }

    /**
     * Endpoint para obtener el calendario de hoy para el usuario actual.
     * GET /api/schedule/today
     */
    public function todaySchedule() {
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        $date = date('Y-m-d');
        
        $sessions = [];
        if ($role == 'student') {
            $sessions = $this->sessionModel->getStudentSessionsByDate($userId, $date);
        } elseif ($role == 'teacher') {
            // Reutilizar lógica existente o mover a service
            $this->db = new Database();
            $this->db->query("SELECT cs.*, s.name as subject_name, c.group_name FROM class_sessions cs 
                              JOIN courses c ON cs.course_id = c.id 
                              JOIN subjects s ON c.subject_id = s.id
                              WHERE c.teacher_id = :tid AND cs.specific_date = :date");
            $this->db->bind(':tid', $userId);
            $this->db->bind(':date', $date);
            $sessions = $this->db->resultSet();
        }

        $this->jsonResponse($sessions);
    }

    /**
     * Helper para respuestas JSON consistentes.
     */
    private function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
