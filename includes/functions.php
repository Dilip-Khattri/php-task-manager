<?php
// Helper Functions for PHP Task Manager

// Format date for display
function formatDate($date, $format = 'M d, Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

// Format datetime for display
function formatDateTime($datetime, $format = 'M d, Y h:i A') {
    if (empty($datetime)) return '';
    return date($format, strtotime($datetime));
}

// Get relative time (e.g., "2 hours ago")
function timeAgo($datetime) {
    if (empty($datetime)) return '';
    
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return formatDate($datetime);
    }
}

// Check if task is overdue
function isOverdue($dueDate, $status) {
    if (empty($dueDate) || $status === 'Completed' || $status === 'Cancelled') {
        return false;
    }
    return strtotime($dueDate) < strtotime('today');
}

// Check if task is due today
function isDueToday($dueDate) {
    if (empty($dueDate)) return false;
    return date('Y-m-d', strtotime($dueDate)) === date('Y-m-d');
}

// Get priority badge class
function getPriorityClass($priority) {
    $classes = [
        'Critical' => 'priority-critical',
        'High' => 'priority-high',
        'Medium' => 'priority-medium',
        'Low' => 'priority-low'
    ];
    return $classes[$priority] ?? 'priority-medium';
}

// Get priority color
function getPriorityColor($priority) {
    $colors = [
        'Critical' => '#8B0000',
        'High' => '#E74C3C',
        'Medium' => '#F39C12',
        'Low' => '#3498DB'
    ];
    return $colors[$priority] ?? '#F39C12';
}

// Get category icon
function getCategoryIcon($category) {
    $icons = [
        'Work' => 'fa-briefcase',
        'Personal' => 'fa-user',
        'Shopping' => 'fa-shopping-cart',
        'Health' => 'fa-heartbeat',
        'Finance' => 'fa-dollar-sign',
        'Education' => 'fa-graduation-cap',
        'Other' => 'fa-folder'
    ];
    return $icons[$category] ?? 'fa-folder';
}

// Get category color
function getCategoryColor($category) {
    $colors = [
        'Work' => '#4A90E2',
        'Personal' => '#9B59B6',
        'Shopping' => '#E67E22',
        'Health' => '#27AE60',
        'Finance' => '#16A085',
        'Education' => '#D35400',
        'Other' => '#7F8C8D'
    ];
    return $colors[$category] ?? '#7F8C8D';
}

// Get status badge class
function getStatusClass($status) {
    $classes = [
        'Pending' => 'status-pending',
        'In Progress' => 'status-progress',
        'Completed' => 'status-completed',
        'Cancelled' => 'status-cancelled'
    ];
    return $classes[$status] ?? 'status-pending';
}

// Get status color
function getStatusColor($status) {
    $colors = [
        'Pending' => '#95A5A6',
        'In Progress' => '#3498DB',
        'Completed' => '#27AE60',
        'Cancelled' => '#E74C3C'
    ];
    return $colors[$status] ?? '#95A5A6';
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate password strength
function isStrongPassword($password) {
    // At least 6 characters
    return strlen($password) >= 6;
}

// Generate random string
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Upload file helper
function uploadFile($file, $allowedTypes = null, $maxSize = null) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload failed'];
    }
    
    $allowedTypes = $allowedTypes ?? ALLOWED_FILE_TYPES;
    $maxSize = $maxSize ?? MAX_FILE_SIZE;
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size exceeds maximum allowed'];
    }
    
    // Check file type
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    // Generate unique filename
    $newFilename = uniqid() . '_' . time() . '.' . $ext;
    $uploadPath = UPLOAD_PATH . $newFilename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['success' => true, 'filename' => $newFilename];
    }
    
    return ['success' => false, 'message' => 'Failed to save file'];
}

// Get user by ID
function getUserById($pdo, $userId) {
    $sql = "SELECT id, username, email, profile_picture, bio, created_at FROM users WHERE id = ?";
    return fetchOne($pdo, $sql, [$userId]);
}

// Get user settings
function getUserSettings($pdo, $userId) {
    $sql = "SELECT * FROM user_settings WHERE user_id = ?";
    $settings = fetchOne($pdo, $sql, [$userId]);
    
    // Create default settings if not exists
    if (!$settings) {
        $sql = "INSERT INTO user_settings (user_id, theme, tasks_per_page, default_view, notifications_enabled) 
                VALUES (?, 'light', 20, 'list', 1)";
        executeQuery($pdo, $sql, [$userId]);
        return getUserSettings($pdo, $userId);
    }
    
    return $settings;
}

// Get task statistics for user
function getTaskStatistics($pdo, $userId) {
    $stats = [];
    
    // Total tasks
    $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ?";
    $result = fetchOne($pdo, $sql, [$userId]);
    $stats['total'] = $result['count'];
    
    // Pending tasks
    $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'Pending'";
    $result = fetchOne($pdo, $sql, [$userId]);
    $stats['pending'] = $result['count'];
    
    // In Progress tasks
    $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'In Progress'";
    $result = fetchOne($pdo, $sql, [$userId]);
    $stats['in_progress'] = $result['count'];
    
    // Completed tasks
    $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'Completed'";
    $result = fetchOne($pdo, $sql, [$userId]);
    $stats['completed'] = $result['count'];
    
    // Overdue tasks
    $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND due_date < CURDATE() AND status NOT IN ('Completed', 'Cancelled')";
    $result = fetchOne($pdo, $sql, [$userId]);
    $stats['overdue'] = $result['count'];
    
    // Due today
    $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND due_date = CURDATE() AND status NOT IN ('Completed', 'Cancelled')";
    $result = fetchOne($pdo, $sql, [$userId]);
    $stats['due_today'] = $result['count'];
    
    // Completion rate
    $stats['completion_rate'] = $stats['total'] > 0 
        ? round(($stats['completed'] / $stats['total']) * 100, 1) 
        : 0;
    
    return $stats;
}

// Truncate text
function truncate($text, $length = 100, $append = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $append;
}
?>
