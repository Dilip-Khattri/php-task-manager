<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'All fields are required';
    } elseif (!isValidEmail($email)) {
        $error = 'Invalid email address';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (!isStrongPassword($password)) {
        $error = 'Password must be at least 6 characters long';
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $existing = fetchOne($pdo, $sql, [$username]);
        
        if ($existing) {
            $error = 'Username already taken';
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            $existing = fetchOne($pdo, $sql, [$email]);
            
            if ($existing) {
                $error = 'Email already registered';
            } else {
                // Create new user
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                
                if (executeQuery($pdo, $sql, [$username, $email, $hashedPassword])) {
                    $userId = getLastInsertId($pdo);
                    
                    // Create default user settings
                    $sql = "INSERT INTO user_settings (user_id) VALUES (?)";
                    executeQuery($pdo, $sql, [$userId]);
                    
                    $success = 'Registration successful! You can now login.';
                    
                    // Auto-login
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}

$pageTitle = 'Register';
$bodyClass = 'auth-page';
include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create Account</h1>
            <p>Join <?php echo APP_NAME; ?> today</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo escape($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo escape($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="auth-form" id="registerForm">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i>
                    Username
                </label>
                <input type="text" id="username" name="username" class="form-control" 
                       placeholder="Choose a username" required 
                       value="<?php echo isset($_POST['username']) ? escape($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Email Address
                </label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="Enter your email" required 
                       value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Choose a password (min 6 characters)" required>
                <small class="form-text">At least 6 characters</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i>
                    Confirm Password
                </label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                       placeholder="Re-enter your password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-user-plus"></i>
                Create Account
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="index.php">Sign in here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
