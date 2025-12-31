<?php

class UserManageController extends Controller {
    private $userModel;
    private $authModel; // We use User.php model for registration logic

    public function __construct() {
        if (!Session::isLoggedIn() || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        $this->userModel = $this->model('UserManagement');
        $this->authModel = $this->model('User');
    }

    public function index() {
        $data = [
            'students' => $this->userModel->getStudents(),
            'teachers' => $this->userModel->getTeachers()
        ];
        $this->view('admin/users/index', $data);
    }

    public function editBasic() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'role' => $_POST['role']
            ];

            if ($this->userModel->updateUserBasic($data)) {
                Session::flash('user_msg', __('msg_user_updated'));
            }
            
            $tab = ($data['role'] == 'teacher') ? '#teachers' : '#students';
            header('location: ' . URL_ROOT . '/admin/userManage' . $tab);
        }
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $role = $_POST['role'];
            $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            if ($this->userModel->updatePassword($id, $newPassword)) {
                Session::flash('user_msg', __('msg_pass_reset'));
            }

            $tab = ($role == 'teacher') ? '#teachers' : '#students';
            header('location: ' . URL_ROOT . '/admin/userManage' . $tab);
        }
    }

    public function delete($id) {
        $user = $this->userModel->getUserById($id);
        if ($user) {
            $role = $user->role;
            if ($this->userModel->deleteUser($id)) {
                Session::flash('user_msg', __('msg_user_deleted'));
            }
            $tab = ($role == 'teacher') ? '#teachers' : '#students';
            header('location: ' . URL_ROOT . '/admin/userManage' . $tab);
        } else {
            header('location: ' . URL_ROOT . '/admin/userManage');
        }
    }
}
