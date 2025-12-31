<?php

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        // User model will be loaded by the parent controller
        $this->userModel = $this->model('User');
    }

    /**
     * Change Password
     */
    public function changePassword()
    {
        // Check if user is logged in
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit();
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_new_password' => trim($_POST['confirm_new_password']),
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_new_password_err' => ''
            ];

            // --- Validation ---
            // Validate Current Password
            if (empty($data['current_password'])) {
                $data['current_password_err'] = 'Please enter your current password.';
            } else {
                // Check if current password is correct
                $loggedInUser = $this->userModel->login($_SESSION['user_email'], $data['current_password']);
                if (!$loggedInUser) {
                    $data['current_password_err'] = 'Your current password is not correct.';
                }
            }

            // Validate New Password
            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Please enter a new password.';
            } elseif (strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'New password must be at least 6 characters long.';
            }

            // Validate Confirm New Password
            if (empty($data['confirm_new_password'])) {
                $data['confirm_new_password_err'] = 'Please confirm your new password.';
            } else {
                if ($data['new_password'] != $data['confirm_new_password']) {
                    $data['confirm_new_password_err'] = 'Passwords do not match.';
                }
            }

            // Make sure errors are empty
            if (empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_new_password_err'])) {
                // All good, update password
                
                // Hash the new password
                $new_hashed_password = password_hash($data['new_password'], PASSWORD_DEFAULT);

                // Update password in DB
                if ($this->userModel->updatePassword($_SESSION['user_id'], $new_hashed_password)) {
                    Session::flash('password_change_success', 'Your password has been updated successfully.');
                    header('location: ' . URL_ROOT . '/users/change-password');
                    exit();
                } else {
                    die('Something went wrong while updating the password.');
                }
            } else {
                // Load view with errors
                $this->view('users/change_password', $data);
            }

        } else {
            // Init data for GET request
            $data = [
                'current_password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_new_password_err' => ''
            ];
            // Load view
            $this->view('users/change_password', $data);
        }
    }
}
