<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Require login
requireLogin();

// Get current user
$currentUser = getUserById($pdo, getCurrentUserId());

$error = '';
$success = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'update_profile') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            
            if (empty($username) || empty($email)) {
                $error = 'Username and email are required';
            } elseif (!isValidEmail($email)) {
                $error = 'Invalid email address';
            } else {
                // Check if username is taken by another user
                $sql = "SELECT id FROM users WHERE username = ? AND id != ?";
                $existing = fetchOne($pdo, $sql, [$username, getCurrentUserId()]);
                
                if ($existing) {
                    $error = 'Username already taken';
                } else {
                    // Check if email is taken by another user
                    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
                    $existing = fetchOne($pdo, $sql, [$email, getCurrentUserId()]);
                    
                    if ($existing) {
                        $error = 'Email already registered';
                    } else {
                        // Update profile
                        $sql = "UPDATE users SET username = ?, email = ?, bio = ? WHERE id = ?";
                        if (executeQuery($pdo, $sql, [$username, $email, $bio, getCurrentUserId()])) {
                            $_SESSION['username'] = $username;
                            $_SESSION['email'] = $email;
                            $success = 'Profile updated successfully';
                            $currentUser = getUserById($pdo, getCurrentUserId());
                        } else {
                            $error = 'Failed to update profile';
                        }
                    }
                }
            }
        } elseif ($action === 'change_password') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'All password fields are required';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'New passwords do not match';
            } elseif (!isStrongPassword($newPassword)) {
                $error = 'Password must be at least 6 characters long';
            } else {
                // Verify current password
                $sql = "SELECT password FROM users WHERE id = ?";
                $user = fetchOne($pdo, $sql, [getCurrentUserId()]);
                
                if (!password_verify($currentPassword, $user['password'])) {
                    $error = 'Current password is incorrect';
                } else {
                    // Update password
                    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                    $sql = "UPDATE users SET password = ? WHERE id = ?";
                    if (executeQuery($pdo, $sql, [$hashedPassword, getCurrentUserId()])) {
                        $success = 'Password changed successfully';
                    } else {
                        $error = 'Failed to change password';
                    }
                }
            }
        }
    }
}

// Get user statistics
$stats = getTaskStatistics($pdo, getCurrentUserId());

// Get recent activities
$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY updated_at DESC LIMIT 10";
$recentActivities = fetchAll($pdo, $sql, [getCurrentUserId()]);

$pageTitle = 'Profile';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="main-container profile-container">
    <div class="page-header">
        <h1><i class="fas fa-user"></i> My Profile</h1>
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
    
    <div class="profile-grid">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar-large">
                    <img src="<?php echo isset($currentUser['profile_picture']) ? 'uploads/' . escape($currentUser['profile_picture']) : 'assets/images/default-avatar.png'; ?>" 
                         alt="Profile Picture" id="profileAvatarPreview">
                    <button class="avatar-upload-btn" onclick="document.getElementById('profilePicture').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="profilePicture" style="display: none;" accept="image/*">
                </div>
                <h2><?php echo escape($currentUser['username']); ?></h2>
                <p class="text-muted"><?php echo escape($currentUser['email']); ?></p>
                <p class="profile-bio"><?php echo escape($currentUser['bio'] ?? 'No bio added yet'); ?></p>
                <p class="text-muted">
                    <i class="fas fa-calendar"></i>
                    Member since <?php echo formatDate($currentUser['created_at'], 'F Y'); ?>
                </p>
            </div>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo $stats['completed']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo $stats['completion_rate']; ?>%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
        
        <!-- Edit Profile Form -->
        <div class="settings-card">
            <h3><i class="fas fa-edit"></i> Edit Profile</h3>
            <form method="POST" action="" class="profile-form">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           value="<?php echo escape($currentUser['username']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo escape($currentUser['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" class="form-control" rows="4" 
                              placeholder="Tell us about yourself"><?php echo escape($currentUser['bio'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </form>
        </div>
        
        <!-- Change Password Form -->
        <div class="settings-card">
            <h3><i class="fas fa-lock"></i> Change Password</h3>
            <form method="POST" action="" class="password-form">
                <input type="hidden" name="action" value="change_password">
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" 
                           class="form-control" required>
                    <small class="form-text">At least 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i>
                    Change Password
                </button>
            </form>
        </div>
        
        <!-- Recent Activity -->
        <div class="settings-card">
            <h3><i class="fas fa-history"></i> Recent Activity</h3>
            <div class="activity-timeline">
                <?php if (!empty($recentActivities)): ?>
                    <?php foreach ($recentActivities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon <?php echo getStatusClass($activity['status']); ?>">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-title"><?php echo escape($activity['title']); ?></p>
                                <p class="activity-meta">
                                    <span class="badge <?php echo getPriorityClass($activity['priority']); ?>">
                                        <?php echo escape($activity['priority']); ?>
                                    </span>
                                    <span class="badge <?php echo getStatusClass($activity['status']); ?>">
                                        <?php echo escape($activity['status']); ?>
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-clock"></i>
                                        <?php echo timeAgo($activity['updated_at']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No recent activity</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
