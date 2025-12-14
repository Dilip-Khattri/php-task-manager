<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Require login
requireLogin();

// Get current user
$currentUser = getUserById($pdo, getCurrentUserId());

// Get user settings
$settings = getUserSettings($pdo, getCurrentUserId());

$error = '';
$success = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = $_POST['theme'] ?? 'light';
    $tasksPerPage = intval($_POST['tasks_per_page'] ?? 20);
    $defaultView = $_POST['default_view'] ?? 'list';
    $notificationsEnabled = isset($_POST['notifications_enabled']) ? 1 : 0;
    
    // Validate input
    $validThemes = ['light', 'dark'];
    $validViews = ['list', 'grid', 'kanban'];
    
    if (!in_array($theme, $validThemes)) {
        $theme = 'light';
    }
    
    if (!in_array($defaultView, $validViews)) {
        $defaultView = 'list';
    }
    
    if ($tasksPerPage < 5 || $tasksPerPage > 100) {
        $tasksPerPage = 20;
    }
    
    // Update settings
    $sql = "UPDATE user_settings SET theme = ?, tasks_per_page = ?, default_view = ?, notifications_enabled = ? WHERE user_id = ?";
    if (executeQuery($pdo, $sql, [$theme, $tasksPerPage, $defaultView, $notificationsEnabled, getCurrentUserId()])) {
        $success = 'Settings saved successfully';
        $settings = getUserSettings($pdo, getCurrentUserId());
    } else {
        $error = 'Failed to save settings';
    }
}

$pageTitle = 'Settings';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="main-container settings-container">
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Settings</h1>
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
    
    <div class="settings-grid">
        <!-- Appearance Settings -->
        <div class="settings-card">
            <h3><i class="fas fa-palette"></i> Appearance</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Theme</label>
                    <div class="theme-options">
                        <label class="theme-option <?php echo $settings['theme'] === 'light' ? 'active' : ''; ?>">
                            <input type="radio" name="theme" value="light" 
                                   <?php echo $settings['theme'] === 'light' ? 'checked' : ''; ?>>
                            <div class="theme-preview light-theme">
                                <i class="fas fa-sun"></i>
                                <span>Light</span>
                            </div>
                        </label>
                        
                        <label class="theme-option <?php echo $settings['theme'] === 'dark' ? 'active' : ''; ?>">
                            <input type="radio" name="theme" value="dark" 
                                   <?php echo $settings['theme'] === 'dark' ? 'checked' : ''; ?>>
                            <div class="theme-preview dark-theme">
                                <i class="fas fa-moon"></i>
                                <span>Dark</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- View Settings -->
                <h3 class="mt-4"><i class="fas fa-eye"></i> Display</h3>
                
                <div class="form-group">
                    <label for="default_view">Default View</label>
                    <select id="default_view" name="default_view" class="form-control">
                        <option value="list" <?php echo $settings['default_view'] === 'list' ? 'selected' : ''; ?>>
                            List View
                        </option>
                        <option value="grid" <?php echo $settings['default_view'] === 'grid' ? 'selected' : ''; ?>>
                            Grid View
                        </option>
                        <option value="kanban" <?php echo $settings['default_view'] === 'kanban' ? 'selected' : ''; ?>>
                            Kanban View
                        </option>
                    </select>
                    <small class="form-text">Choose your preferred default view for tasks</small>
                </div>
                
                <div class="form-group">
                    <label for="tasks_per_page">Tasks Per Page</label>
                    <input type="number" id="tasks_per_page" name="tasks_per_page" 
                           class="form-control" min="5" max="100" 
                           value="<?php echo escape($settings['tasks_per_page']); ?>">
                    <small class="form-text">Number of tasks to display per page (5-100)</small>
                </div>
                
                <!-- Notifications -->
                <h3 class="mt-4"><i class="fas fa-bell"></i> Notifications</h3>
                
                <div class="form-group form-check">
                    <label class="checkbox-label">
                        <input type="checkbox" name="notifications_enabled" 
                               <?php echo $settings['notifications_enabled'] ? 'checked' : ''; ?>>
                        <span>Enable notifications</span>
                    </label>
                    <small class="form-text">Receive notifications for task reminders and updates</small>
                </div>
                
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-save"></i>
                    Save Settings
                </button>
            </form>
        </div>
        
        <!-- Account Information -->
        <div class="settings-card">
            <h3><i class="fas fa-user-circle"></i> Account Information</h3>
            <div class="account-info">
                <div class="info-item">
                    <label>Username</label>
                    <p><?php echo escape($currentUser['username']); ?></p>
                </div>
                
                <div class="info-item">
                    <label>Email</label>
                    <p><?php echo escape($currentUser['email']); ?></p>
                </div>
                
                <div class="info-item">
                    <label>Member Since</label>
                    <p><?php echo formatDate($currentUser['created_at'], 'F d, Y'); ?></p>
                </div>
                
                <div class="info-item">
                    <label>Last Updated</label>
                    <p><?php echo formatDateTime($currentUser['updated_at']); ?></p>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="profile.php" class="btn btn-secondary">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </a>
            </div>
        </div>
        
        <!-- Keyboard Shortcuts -->
        <div class="settings-card">
            <h3><i class="fas fa-keyboard"></i> Keyboard Shortcuts</h3>
            <div class="shortcuts-list">
                <div class="shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>N</kbd>
                    <span>Add new task</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>F</kbd>
                    <span>Search tasks</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>L</kbd>
                    <span>Switch to list view</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>G</kbd>
                    <span>Switch to grid view</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>K</kbd>
                    <span>Switch to kanban view</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Esc</kbd>
                    <span>Close modal/dialog</span>
                </div>
            </div>
        </div>
        
        <!-- About -->
        <div class="settings-card">
            <h3><i class="fas fa-info-circle"></i> About</h3>
            <div class="about-info">
                <p><strong><?php echo APP_NAME; ?></strong></p>
                <p>Version <?php echo APP_VERSION; ?></p>
                <p class="text-muted mt-2">
                    A modern task management application built with PHP, MySQL, and JavaScript.
                </p>
                <div class="mt-3">
                    <a href="https://github.com/Dilip-Khattri/php-task-manager" 
                       target="_blank" class="btn btn-secondary btn-sm">
                        <i class="fab fa-github"></i>
                        View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Theme switcher preview
document.querySelectorAll('input[name="theme"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.body.setAttribute('data-theme', this.value);
        localStorage.setItem('theme', this.value);
        
        // Update active class
        document.querySelectorAll('.theme-option').forEach(option => {
            option.classList.remove('active');
        });
        this.closest('.theme-option').classList.add('active');
    });
});
</script>

<?php include 'includes/footer.php'; ?>
