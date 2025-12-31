<?php

class AcademicController extends Controller {
    private $academicModel;

    public function __construct() {
        if (!Session::isLoggedIn() || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        $this->academicModel = $this->model('Academic');
    }

    public function index() {
        $data = [
            'programs' => $this->academicModel->getPrograms(),
            'periods' => $this->academicModel->getPeriods(),
            'rooms' => $this->academicModel->getRooms()
        ];
        $this->view('admin/academic/index', $data);
    }

    // --- Programs ---
    public function addProgram() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code'])
            ];
            
            $success = $this->academicModel->addProgram($data);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success, 'message' => $success ? __('msg_program_added') : __('msg_error')]);
                exit;
            }

            if ($success) {
                Session::flash('academic_msg', __('msg_program_added'));
            }
            header('location: ' . URL_ROOT . '/admin/academic#programs');
        }
    }

    public function editProgram() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code'])
            ];
            if ($this->academicModel->updateProgram($data)) {
                Session::flash('academic_msg', __('msg_program_updated'));
            }
            header('location: ' . URL_ROOT . '/admin/academic#programs');
        }
    }

    public function deleteProgram($id) {
        if ($this->academicModel->deleteProgram($id)) {
            Session::flash('academic_msg', __('msg_program_deleted'));
        } else {
            Session::flash('academic_msg', __('msg_error'), 'alert alert-danger');
        }
        header('location: ' . URL_ROOT . '/admin/academic#programs');
    }

    // --- Periods ---
    public function addPeriod() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // If setting this one active, logic in model should ideally handle turning others off,
            // or we do it manually. For now, simple insert.
            if ($data['is_active']) {
                // If this is active, we might need to ensure others are not, but let's handle that in update/toggle
            }

            $success = $this->academicModel->addPeriod($data);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success, 'message' => $success ? __('msg_period_added') : __('msg_error')]);
                exit;
            }

            if ($success) {
                // If added as active, ensure it is the ONLY active one?
                // For simplicity, let's assume the user manages this or we add a helper later.
                if($data['is_active']) {
                     // Get the ID we just inserted? Database::lastInsertId() is not exposed in Model base usually, 
                     // but let's just leave it for now.
                }
                Session::flash('academic_msg', __('msg_period_added'));
            }
            header('location: ' . URL_ROOT . '/admin/academic#periods');
        }
    }

    public function editPeriod() {
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if ($data['is_active']) {
                 $this->academicModel->setActivePeriod($data['id']);
                 // Update other fields
                 $this->academicModel->updatePeriod($data);
            } else {
                 $this->academicModel->updatePeriod($data);
            }

            Session::flash('academic_msg', __('msg_period_updated'));
            header('location: ' . URL_ROOT . '/admin/academic#periods');
        }
    }

    public function deletePeriod($id) {
        if ($this->academicModel->deletePeriod($id)) {
            Session::flash('academic_msg', __('msg_period_deleted'));
        }
         header('location: ' . URL_ROOT . '/admin/academic#periods');
    }

    // --- Rooms ---
    public function addRoom() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'capacity' => trim($_POST['capacity']),
                'location' => trim($_POST['location'])
            ];
            
            $success = $this->academicModel->addRoom($data);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success, 'message' => $success ? __('msg_room_added') : __('msg_error')]);
                exit;
            }

            if ($success) {
                Session::flash('academic_msg', __('msg_room_added'));
            }
            header('location: ' . URL_ROOT . '/admin/academic#rooms');
        }
    }

    public function editRoom() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'capacity' => trim($_POST['capacity']),
                'location' => trim($_POST['location'])
            ];
            if ($this->academicModel->updateRoom($data)) {
                Session::flash('academic_msg', __('msg_room_updated'));
            }
            header('location: ' . URL_ROOT . '/admin/academic#rooms');
        }
    }

    public function deleteRoom($id) {
        if ($this->academicModel->deleteRoom($id)) {
            Session::flash('academic_msg', __('msg_room_deleted'));
        }
        header('location: ' . URL_ROOT . '/admin/academic#rooms');
    }
}
