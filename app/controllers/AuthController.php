<?php

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        // Check if user is an admin
        if (!Session::isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            Session::flash('auth_error', 'You are not authorized to view this page', 'alert alert-danger');
            header('location: ' . URL_ROOT);
            exit();
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
                die('Invalid CSRF Token');
            }

            // Process form
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'role_err' => ''
            ];

            // Instantiate Validator
            $validator = new Validator($_POST);
            
            // Define rules
            $validator->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|match:password',
                'role' => 'required|in:admin,teacher,student'
            ]);

            // Map Validator errors to view data
            $data['name_err'] = $validator->getError('name');
            $data['email_err'] = $validator->getError('email');
            $data['password_err'] = $validator->getError('password');
            $data['confirm_password_err'] = $validator->getError('confirm_password');
            $data['role_err'] = $validator->getError('role');

            // Additional DB check: Email Uniqueness
            // Only check if email format is valid so far
            if (empty($data['email_err']) && $this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email is already taken';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['role_err'])) {
                // Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if ($this->userModel->register($data)) {
                    Session::flash('register_success', __('msg_reg_success'));
                    header('location: ' . URL_ROOT . '/auth/register');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }

        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'role' => 'student', // Default role
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'role_err' => ''
            ];
            // Load view
            $this->view('auth/register', $data);
        }
    }

    public function import()
    {
        // Check if user is an admin
        if (!Session::isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            Session::flash('auth_error', 'You are not authorized to perform this action', 'alert alert-danger');
            header('location: ' . URL_ROOT);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
                die('Invalid CSRF Token');
            }

            $file = $_FILES['file'];
            $notificationModel = $this->model('Notification');

            // Check for errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                Session::flash('import_msg', 'Error uploading file.', 'alert alert-danger');
                header('location: ' . URL_ROOT . '/auth/register');
                exit();
            }

            // Check file extension
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'csv') {
                Session::flash('import_msg', 'Invalid file type. Please upload a CSV file.', 'alert alert-danger');
                header('location: ' . URL_ROOT . '/auth/register');
                exit();
            }

            // Open file
            $handle = fopen($file['tmp_name'], 'r');
            if ($handle === false) {
                Session::flash('import_msg', 'Could not open file.', 'alert alert-danger');
                header('location: ' . URL_ROOT . '/auth/register');
                exit();
            }

            // Detect Delimiter (Comma or Semicolon for Excel/Spanish)
            $firstLine = fgets($handle);
            rewind($handle); // Reset pointer
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

            $successCount = 0;
            $failCount = 0;
            $row = 0;
            $errors = [];
            $processedEmails = []; // To detect duplicates within the same CSV

            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $row++;
                
                // Clean BOM (Byte Order Mark) from first cell if exists
                if ($row == 1) {
                    $data[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[0]);
                }

                // Skip header row
                if ($row == 1 && (strtolower($data[1]) == 'email' || strtolower($data[0]) == 'name')) {
                    continue;
                }

                // 1. Column Count Validation
                if (count($data) < 4) {
                    $failCount++;
                    $errors[] = "Row $row: Incorrect format (Found " . count($data) . " columns, expected 4).";
                    continue;
                }

                $userData = [
                    'name' => trim($data[0]),
                    'email' => trim($data[1]),
                    'password' => trim($data[2]),
                    'role' => strtolower(trim($data[3]))
                ];

                // 2. Empty Field Validation
                if (empty($userData['name']) || empty($userData['email']) || empty($userData['password']) || empty($userData['role'])) {
                    $failCount++;
                    $errors[] = "Row $row: All fields are required.";
                    continue;
                }

                // 3. Email Format Validation
                if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                    $failCount++;
                    $errors[] = "Row $row: Invalid email format ({$userData['email']}).";
                    continue;
                }

                // 4. Duplicate in current CSV batch
                if (in_array($userData['email'], $processedEmails)) {
                    $failCount++;
                    $errors[] = "Row $row: Duplicate email found within the same file.";
                    continue;
                }
                $processedEmails[] = $userData['email'];

                // 5. Duplicate in Database
                if ($this->userModel->findUserByEmail($userData['email'])) {
                    $failCount++;
                    $errors[] = "Row $row: Email '{$userData['email']}' is already registered in the system.";
                    continue;
                }

                // 6. Password Length Validation
                if (strlen($userData['password']) < 6) {
                    $failCount++;
                    $errors[] = "Row $row: Password too short (Minimum 6 characters).";
                    continue;
                }

                // 7. Strict Role Validation
                $validRoles = ['admin', 'teacher', 'student'];
                if (!in_array($userData['role'], $validRoles)) {
                    $failCount++;
                    $errors[] = "Row $row: Invalid role '{$userData['role']}'. Use: admin, teacher, or student.";
                    continue;
                }

                // Hash Password
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);

                // Register
                if ($this->userModel->register($userData)) {
                    $successCount++;
                } else {
                    $failCount++;
                    $errors[] = "Row $row: Database error while creating user.";
                }
            }

            fclose($handle);

            // Save notification in DB for Admin
            $notifType = ($failCount > 0) ? 'warning' : 'success';
            $notifTitle = "Bulk Import Completed";
            $notifMsg = "Processed $row rows. Success: $successCount, Failed: $failCount.";
            $notificationModel->add($_SESSION['user_id'], $notifTitle, $notifMsg, $notifType);

            // Store detailed results in Session for Modal
            $_SESSION['import_results'] = [
                'success' => $successCount,
                'failed' => $failCount,
                'errors' => $errors
            ];
            
            header('location: ' . URL_ROOT . '/auth/register');
        } else {
            header('location: ' . URL_ROOT . '/auth/register');
        }
    }

    public function template()
    {
        // Check if user is an admin
        if (!Session::isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            header('location: ' . URL_ROOT);
            exit();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=users_template.csv');

        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['Name', 'Email', 'Password', 'Role (student/teacher/admin)']);
        
        // Examples
        fputcsv($output, ['John Doe', 'john.doe@example.com', 'SecurePass123', 'student']);
        fputcsv($output, ['Jane Teacher', 'jane.prof@example.com', 'MySecretPass', 'teacher']);
        
        fclose($output);
        exit();
    }

    public function login()
    {
        // Check if logged in and redirect to appropriate dashboard
        if (Session::isLoggedIn()) {
            if ($_SESSION['user_role'] == 'admin') {
                header('location: ' . URL_ROOT . '/admin');
            } elseif ($_SESSION['user_role'] == 'teacher') {
                header('location: ' . URL_ROOT . '/attendance');
            } else {
                header('location: ' . URL_ROOT . '/profile');
            }
            exit;
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
                die('Invalid CSRF Token');
            }

            // Process form
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if (!$this->userModel->findUserByEmail($data['email'])) {
                // User not found
                $data['email_err'] = 'No user found with that email';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];
            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role; // Add role to session
        Session::flash('login_success', 'Welcome, ' . $user->name);

        // Role-based redirection
        if ($user->role == 'admin') {
            header('location: ' . URL_ROOT . '/admin');
        } elseif ($user->role == 'teacher') {
            header('location: ' . URL_ROOT . '/attendance');
        } else {
            header('location: ' . URL_ROOT . '/profile');
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        Session::flash('logout_success', 'You have been logged out');
        header('location: ' . URL_ROOT . '/auth/login');
    }
}

