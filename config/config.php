<?php
// PHP Task Manager - Application Configuration
// Version: 1.0.0

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Application settings
define('APP_NAME', 'PHP Task Manager');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/php-task-manager');

// Directory paths
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('ASSETS_PATH', BASE_PATH . '/assets/');

// File upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt']);

// Pagination settings
define('TASKS_PER_PAGE', 20);

// Security settings
define('SESSION_LIFETIME', 3600 * 24); // 24 hours
define('REMEMBER_ME_LIFETIME', 3600 * 24 * 30); // 30 days

// Timezone
date_default_timezone_set('UTC');

// Error reporting (set to 0 in production)
if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Include database configuration
$dbConfigFile = BASE_PATH . '/config/database.php';
if (!file_exists($dbConfigFile)) {
    $dbConfigFile = BASE_PATH . '/config/database.example.php';
    if (!file_exists($dbConfigFile)) {
        die('Database configuration file not found. Please copy config/database.example.php to config/database.php and configure your database credentials.');
    }
}
require_once $dbConfigFile;

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Helper function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}

// Helper function to redirect if already logged in
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit();
    }
}

// Helper function to get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Helper function to sanitize output
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Helper function for JSON response
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
?>
