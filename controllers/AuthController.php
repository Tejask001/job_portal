<?php
session_start(); // Make sure this is at the VERY top of the file!

require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/functions.php'; // Include the functions file

class AuthController
{
    private $userModel;
    private $pdo; // Store the PDO connection

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->pdo = $pdo;  // Store the PDO object
    }

    public function register($user_type, $name, $email, $password)
    {
        global $pdo;

        // Validate input (you should add more validation)
        if (empty($user_type) || empty($name) || empty($email) || empty($password)) {
            $_SESSION['error_message'] = "All fields are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/auth/register.php'); // Redirect back
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Invalid email format.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/auth/register.php');
            return;
        }

        // Check if email already exists
        if ($this->userModel->getUserByEmail($email)) {
            $_SESSION['error_message'] = "Email already registered.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/auth/register.php');
            return;
        }

        $user_id = $this->userModel->createUser($user_type, $name, $email, $password);

        if ($user_id) {
            $_SESSION['success_message'] = "Registration successful! Please login.";
            redirect('/job_portal/views/auth/login.php');
        } else {
            $_SESSION['error_message'] = "Registration failed. Please try again.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/auth/register.php');
        }
    }

    public function login($email, $password)
    {
        global $pdo;

        // Validate input
        if (empty($email) || empty($password)) {
            $_SESSION['error_message'] = "Email and password are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/auth/login.php');
            return;
        }

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_name'] = $user['name'];

            switch ($user['user_type']) {
                case 'admin':
                    redirect('/job_portal/views/admin/dashboard.php');
                    break;
                case 'company':
                    redirect('/job_portal/views/company/dashboard.php');
                    break;
                case 'seeker':
                    redirect('/job_portal/views/seeker/dashboard.php');
                    break;
                default:
                    redirect('/job_portal/index.php'); // Or a generic landing page
            }
        } else {
            // Login failed
            $_SESSION['error_message'] = "Invalid email or password.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/auth/login.php');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        redirect('/job_portal/index.php');
    }

    // Handle requests based on the 'action' parameter
    public function handleRequest()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'register':
                    //Call the register function
                    $this->register(
                        $_POST['user_type'] ?? '',
                        $_POST['name'] ?? '',
                        $_POST['email'] ?? '',
                        $_POST['password'] ?? ''
                    );
                    break;
                case 'login':
                    //Call the login function
                    $this->login(
                        $_POST['email'] ?? '',
                        $_POST['password'] ?? ''
                    );
                    break;
                case 'logout':
                    $this->logout();
                    break;
                default:
                    // Invalid action
                    echo "Invalid action."; //Or redirect to homepage
                    break;
            }
        }
    }
}

// Create an instance of the AuthController and handle the request
$authController = new AuthController($pdo);
$authController->handleRequest();
