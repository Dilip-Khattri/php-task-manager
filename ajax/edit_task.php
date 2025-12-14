<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Not authenticated'], 401);
}

// Get POST data
$taskId = intval($_POST['task_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$dueDate = $_POST['due_date'] ?? null;
$dueTime = $_POST['due_time'] ?? null;
$priority = $_POST['priority'] ?? 'Medium';
$category = $_POST['category'] ?? 'Personal';
$status = $_POST['status'] ?? 'Pending';
$progress = intval($_POST['progress'] ?? 0);
$tags = trim($_POST['tags'] ?? '');
$isFavorite = isset($_POST['is_favorite']) ? 1 : 0;

// Validate required fields
if ($taskId <= 0) {
    jsonResponse(['success' => false, 'message' => 'Invalid task ID'], 400);
}

if (empty($title)) {
    jsonResponse(['success' => false, 'message' => 'Title is required'], 400);
}

// Verify task belongs to current user
$sql = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
$task = fetchOne($pdo, $sql, [$taskId, getCurrentUserId()]);

if (!$task) {
    jsonResponse(['success' => false, 'message' => 'Task not found or access denied'], 404);
}

// Validate enums
$validPriorities = ['Critical', 'High', 'Medium', 'Low'];
$validCategories = ['Work', 'Personal', 'Shopping', 'Health', 'Finance', 'Education', 'Other'];
$validStatuses = ['Pending', 'In Progress', 'Completed', 'Cancelled'];

if (!in_array($priority, $validPriorities)) {
    $priority = 'Medium';
}

if (!in_array($category, $validCategories)) {
    $category = 'Personal';
}

if (!in_array($status, $validStatuses)) {
    $status = 'Pending';
}

// Validate progress
if ($progress < 0 || $progress > 100) {
    $progress = 0;
}

// Empty date handling
if (empty($dueDate)) {
    $dueDate = null;
}

if (empty($dueTime)) {
    $dueTime = null;
}

// Set completed_at if status is Completed
$completedAt = null;
if ($status === 'Completed') {
    // Check if task wasn't already completed
    $sql = "SELECT completed_at FROM tasks WHERE id = ?";
    $currentTask = fetchOne($pdo, $sql, [$taskId]);
    $completedAt = $currentTask['completed_at'] ?? date('Y-m-d H:i:s');
}

// Update task
$sql = "UPDATE tasks SET 
        title = ?, 
        description = ?, 
        due_date = ?, 
        due_time = ?, 
        priority = ?, 
        category = ?, 
        status = ?, 
        progress = ?, 
        tags = ?, 
        is_favorite = ?,
        completed_at = ?
        WHERE id = ? AND user_id = ?";

$params = [
    $title,
    $description,
    $dueDate,
    $dueTime,
    $priority,
    $category,
    $status,
    $progress,
    $tags,
    $isFavorite,
    $completedAt,
    $taskId,
    getCurrentUserId()
];

if (executeQuery($pdo, $sql, $params)) {
    // Fetch updated task
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $updatedTask = fetchOne($pdo, $sql, [$taskId]);
    
    jsonResponse([
        'success' => true,
        'message' => 'Task updated successfully',
        'task' => $updatedTask
    ]);
} else {
    jsonResponse(['success' => false, 'message' => 'Failed to update task'], 500);
}
?>
