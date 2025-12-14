<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Not authenticated'], 401);
}

// Get task IDs
$taskIds = $_POST['task_ids'] ?? [];

// Validate task IDs
if (empty($taskIds) || !is_array($taskIds)) {
    jsonResponse(['success' => false, 'message' => 'No tasks selected'], 400);
}

// Sanitize task IDs
$taskIds = array_map('intval', $taskIds);
$taskIds = array_filter($taskIds, function($id) {
    return $id > 0;
});

if (empty($taskIds)) {
    jsonResponse(['success' => false, 'message' => 'Invalid task IDs'], 400);
}

// Create placeholders for IN clause
$placeholders = implode(',', array_fill(0, count($taskIds), '?'));

// Delete tasks that belong to current user
$sql = "DELETE FROM tasks WHERE id IN ($placeholders) AND user_id = ?";
$params = array_merge($taskIds, [getCurrentUserId()]);

if (executeQuery($pdo, $sql, $params)) {
    jsonResponse([
        'success' => true,
        'message' => count($taskIds) . ' task(s) deleted successfully',
        'count' => count($taskIds)
    ]);
} else {
    jsonResponse(['success' => false, 'message' => 'Failed to delete tasks'], 500);
}
?>
