<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Not authenticated'], 401);
}

// Get task ID
$taskId = intval($_POST['task_id'] ?? 0);

// Validate task ID
if ($taskId <= 0) {
    jsonResponse(['success' => false, 'message' => 'Invalid task ID'], 400);
}

// Verify task belongs to current user
$sql = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
$task = fetchOne($pdo, $sql, [$taskId, getCurrentUserId()]);

if (!$task) {
    jsonResponse(['success' => false, 'message' => 'Task not found or access denied'], 404);
}

// Delete task
$sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";

if (executeQuery($pdo, $sql, [$taskId, getCurrentUserId()])) {
    jsonResponse([
        'success' => true,
        'message' => 'Task deleted successfully'
    ]);
} else {
    jsonResponse(['success' => false, 'message' => 'Failed to delete task'], 500);
}
?>
