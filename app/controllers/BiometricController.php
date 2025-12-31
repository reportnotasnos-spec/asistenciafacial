<?php

class BiometricController extends Controller
{
    private $biometricModel;
    private $userModel;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            header('location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        $this->biometricModel = $this->model('UserBiometric');
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get JSON input
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (isset($data['descriptor'])) {
                // $data['descriptor'] is the array of numbers from face-api.js
                // We store it as a JSON string
                $jsonDescriptor = json_encode($data['descriptor']);
                
                // Handle Image Upload
                $imagePath = null;
                if (isset($data['image'])) {
                    $imageData = $data['image'];
                    // Remove the "data:image/jpeg;base64," part
                    $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
                    $imageData = str_replace(' ', '+', $imageData);
                    $imageBinary = base64_decode($imageData);
                    
                    $fileName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.jpg';
                    $filePath = 'public/uploads/avatars/' . $fileName; // Path for saving
                    $dbPath = 'uploads/avatars/' . $fileName; // Path for URL access (relative to public)
                    
                    // We need to go up one level from app/controllers to root, then into public...
                    // Actually, let's use the defined constants or relative paths carefully.
                    // Assuming script runs from public/index.php, cwd is public/. 
                    // So we can write directly to 'uploads/avatars/'
                    
                    if (file_put_contents('uploads/avatars/' . $fileName, $imageBinary)) {
                        $imagePath = $dbPath;
                        // Update User Profile
                        $this->userModel->updateProfilePicture($_SESSION['user_id'], $_SESSION['user_role'], $imagePath);
                    }
                }

                if ($this->biometricModel->register($_SESSION['user_id'], $jsonDescriptor)) {
                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'message' => 'Biometric data and photo registered']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Database error']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'No descriptor provided']);
            }
            exit; // Stop execution for API response
        } else {
            // Render View
            $data = [
                'title' => 'Register Biometrics',
                'description' => 'Please position your face in the camera frame.'
            ];
            $this->view('biometrics/register', $data);
        }
    }
}
