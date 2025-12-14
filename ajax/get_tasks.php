<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Not authenticated'], 401);
}

$userId = getCurrentUserId();

// Get filter parameters
$status = $_GET['status'] ?? '';
$priority = $_GET['priority'] ?? '';
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$view = $_GET['view'] ?? 'list';

// Build query
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$userId];

// Add filters
if (!empty($status)) {
    $sql .= " AND status = ?";
    $params[] = $status;
}

if (!empty($priority)) {
    $sql .= " AND priority = ?";
    $params[] = $priority;
}

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

if (!empty($search)) {
    $sql .= " AND (title LIKE ? OR description LIKE ? OR tags LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Order by
$sql .= " ORDER BY is_favorite DESC, due_date ASC, created_at DESC";

// Execute query
$tasks = fetchAll($pdo, $sql, $params);

// Format tasks for output
$formattedTasks = [];
foreach ($tasks as $task) {
    $formattedTasks[] = [
        'id' => $task['id'],
        'title' => $task['title'],
        'description' => $task['description'],
        'due_date' => $task['due_date'],
        'due_time' => $task['due_time'],
        'priority' => $task['priority'],
        'category' => $task['category'],
        'status' => $task['status'],
        'progress' => $task['progress'],
        'tags' => $task['tags'],
        'is_favorite' => $task['is_favorite'],
        'completed_at' => $task['completed_at'],
        'created_at' => $task['created_at'],
        'updated_at' => $task['updated_at'],
        'is_overdue' => isOverdue($task['due_date'], $task['status']),
        'is_due_today' => isDueToday($task['due_date']),
        'priority_class' => getPriorityClass($task['priority']),
        'priority_color' => getPriorityColor($task['priority']),
        'category_icon' => getCategoryIcon($task['category']),
        'category_color' => getCategoryColor($task['category']),
        'status_class' => getStatusClass($task['status']),
        'status_color' => getStatusColor($task['status']),
        'formatted_due_date' => formatDate($task['due_date']),
        'time_ago' => timeAgo($task['updated_at'])
    ];
}

jsonResponse([
    'success' => true,
    'tasks' => $formattedTasks,
    'count' => count($formattedTasks)
]);
?>
