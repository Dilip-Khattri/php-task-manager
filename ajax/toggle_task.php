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
$sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
$task = fetchOne($pdo, $sql, [$taskId, getCurrentUserId()]);

if (!$task) {
    jsonResponse(['success' => false, 'message' => 'Task not found or access denied'], 404);
}

// Toggle status
$newStatus = ($task['status'] === 'Completed') ? 'Pending' : 'Completed';
$completedAt = ($newStatus === 'Completed') ? date('Y-m-d H:i:s') : null;
$progress = ($newStatus === 'Completed') ? 100 : $task['progress'];

// Update task
$sql = "UPDATE tasks SET status = ?, completed_at = ?, progress = ? WHERE id = ? AND user_id = ?";
$params = [$newStatus, $completedAt, $progress, $taskId, getCurrentUserId()];

if (executeQuery($pdo, $sql, $params)) {
    // Fetch updated task
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $updatedTask = fetchOne($pdo, $sql, [$taskId]);
    
    jsonResponse([
        'success' => true,
        'message' => 'Task status updated',
        'task' => $updatedTask,
        'new_status' => $newStatus
    ]);
} else {
    jsonResponse(['success' => false, 'message' => 'Failed to update task'], 500);
}
?>
