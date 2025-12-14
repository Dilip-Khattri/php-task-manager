<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        // Fetch user from database
        $sql = "SELECT * FROM users WHERE email = ?";
        $user = fetchOne($pdo, $sql, [$email]);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            
            // Set remember me cookie
            if ($remember) {
                $token = generateRandomString();
                setcookie('remember_token', $token, time() + REMEMBER_ME_LIFETIME, '/');
                // In production, store token in database
            }
            
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    }
}

$pageTitle = 'Login';
$bodyClass = 'auth-page';
include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-tasks"></i>
            </div>
            <h1><?php echo APP_NAME; ?></h1>
            <p>Sign in to manage your tasks</p>
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
        
        <form method="POST" action="" class="auth-form" id="loginForm">
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
                       placeholder="Enter your password" required>
            </div>
            
            <div class="form-group form-check">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Sign up here</a></p>
        </div>
        
        <div class="demo-credentials">
            <h4><i class="fas fa-info-circle"></i> Demo Credentials</h4>
            <p><strong>Email:</strong> demo@taskmanager.com</p>
            <p><strong>Password:</strong> demo123</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
