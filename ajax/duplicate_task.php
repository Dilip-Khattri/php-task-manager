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

// Verify task belongs to current user and fetch it
$sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
$task = fetchOne($pdo, $sql, [$taskId, getCurrentUserId()]);

if (!$task) {
    jsonResponse(['success' => false, 'message' => 'Task not found or access denied'], 404);
}

// Create duplicate
$sql = "INSERT INTO tasks (user_id, title, description, due_date, due_time, priority, category, status, progress, tags, is_favorite) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    getCurrentUserId(),
    $task['title'] . ' (Copy)',
    $task['description'],
    $task['due_date'],
    $task['due_time'],
    $task['priority'],
    $task['category'],
    'Pending', // Reset status to Pending
    0, // Reset progress
    $task['tags'],
    0 // Don't copy favorite status
];

if (executeQuery($pdo, $sql, $params)) {
    $newTaskId = getLastInsertId($pdo);
    
    // Fetch the new task
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $newTask = fetchOne($pdo, $sql, [$newTaskId]);
    
    jsonResponse([
        'success' => true,
        'message' => 'Task duplicated successfully',
        'task' => $newTask
    ]);
} else {
    jsonResponse(['success' => false, 'message' => 'Failed to duplicate task'], 500);
}
?>
