<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Not authenticated'], 401);
}

// Get POST data
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
if (empty($title)) {
    jsonResponse(['success' => false, 'message' => 'Title is required'], 400);
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
$completedAt = ($status === 'Completed') ? date('Y-m-d H:i:s') : null;

// Insert task
$sql = "INSERT INTO tasks (user_id, title, description, due_date, due_time, priority, category, status, progress, tags, is_favorite, completed_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    getCurrentUserId(),
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
    $completedAt
];

if (executeQuery($pdo, $sql, $params)) {
    $taskId = getLastInsertId($pdo);
    
    // Fetch the newly created task
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $task = fetchOne($pdo, $sql, [$taskId]);
    
    jsonResponse([
        'success' => true,
        'message' => 'Task created successfully',
        'task' => $task
    ]);
} else {
    jsonResponse(['success' => false, 'message' => 'Failed to create task'], 500);
}
?>
