<?php

class AdminController extends Controller {
    private $dashboardModel;
    private $settingsModel;
    private $sessionModel;

    public function __construct() {
        // Security Check
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }

        if ($_SESSION['user_role'] != 'admin') {
            // Redirect non-admins to their respective home pages
            if ($_SESSION['user_role'] == 'teacher') {
                header('location: ' . URL_ROOT . '/attendance');
            } else {
                header('location: ' . URL_ROOT . '/profile');
            }
            exit;
        }

        $this->dashboardModel = $this->model('Dashboard');
        $this->settingsModel = $this->model('SystemSetting');
        $this->sessionModel = $this->model('ClassSession');
    }

    public function index() {
        // Auto-close past sessions to ensure stats accuracy
        $this->sessionModel->closePastSessions();

        $stats = $this->dashboardModel->getCounts();
        $recentLogs = $this->dashboardModel->getRecentAttendance(5);

        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recent_logs' => $recentLogs
        ];

        $this->view('admin/index', $data);
    }

    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $settings = $_POST['settings'] ?? [];
            if ($this->settingsModel->updateMultiple($settings)) {
                Session::flash('settings_msg', __('msg_settings_updated'));
            } else {
                Session::flash('settings_msg', __('msg_error'), 'alert alert-danger');
            }
            header('location: ' . URL_ROOT . '/admin/settings');
            exit;
        }

        $data = [
            'title' => 'System Settings',
            'settings' => $this->settingsModel->getAll()
        ];

        $this->view('admin/settings', $data);
    }
}
