<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Require login
requireLogin();

// Get current user
$currentUser = getUserById($pdo, getCurrentUserId());

// Get user settings
$settings = getUserSettings($pdo, getCurrentUserId());

// Get task statistics
$stats = getTaskStatistics($pdo, getCurrentUserId());

$pageTitle = 'Dashboard';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="main-container">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-total">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>
        
        <div class="stat-card stat-pending">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        
        <div class="stat-card stat-progress">
            <div class="stat-icon">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $stats['in_progress']; ?></div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
        
        <div class="stat-card stat-completed">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $stats['completed']; ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        
        <div class="stat-card stat-overdue">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $stats['overdue']; ?></div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>
        
        <div class="stat-card stat-today">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $stats['due_today']; ?></div>
                <div class="stat-label">Due Today</div>
            </div>
        </div>
        
        <div class="stat-card stat-rate">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $stats['completion_rate']; ?>%</div>
                <div class="stat-label">Completion Rate</div>
            </div>
        </div>
        
        <div class="stat-card stat-action">
            <button class="btn btn-primary btn-add-task" id="addTaskBtn">
                <i class="fas fa-plus"></i>
                <span>Add New Task</span>
            </button>
        </div>
    </div>

    <!-- Task Controls -->
    <div class="task-controls">
        <div class="view-switcher">
            <button class="view-btn active" data-view="list" title="List View">
                <i class="fas fa-list"></i>
            </button>
            <button class="view-btn" data-view="grid" title="Grid View">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" data-view="kanban" title="Kanban View">
                <i class="fas fa-columns"></i>
            </button>
        </div>
        
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="taskSearch" placeholder="Search tasks..." class="search-input">
        </div>
        
        <button class="btn btn-secondary" id="toggleFiltersBtn">
            <i class="fas fa-filter"></i>
            Filters
        </button>
        
        <button class="btn btn-secondary" id="bulkDeleteBtn" style="display: none;">
            <i class="fas fa-trash"></i>
            Delete Selected
        </button>
    </div>

    <!-- Filters Panel -->
    <div class="filters-panel" id="filtersPanel" style="display: none;">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Status</label>
                <select id="filterStatus" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Priority</label>
                <select id="filterPriority" class="form-control">
                    <option value="">All Priorities</option>
                    <option value="Critical">Critical</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Category</label>
                <select id="filterCategory" class="form-control">
                    <option value="">All Categories</option>
                    <option value="Work">Work</option>
                    <option value="Personal">Personal</option>
                    <option value="Shopping">Shopping</option>
                    <option value="Health">Health</option>
                    <option value="Finance">Finance</option>
                    <option value="Education">Education</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="filter-group">
                <button class="btn btn-secondary btn-block" id="clearFiltersBtn">
                    <i class="fas fa-times"></i>
                    Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Tasks Container -->
    <div class="tasks-container">
        <!-- List View -->
        <div class="tasks-list-view active" id="listView">
            <div id="tasksListContainer" class="tasks-list">
                <!-- Tasks will be loaded here via AJAX -->
                <div class="loading-state">
                    <div class="spinner"></div>
                    <p>Loading tasks...</p>
                </div>
            </div>
        </div>
        
        <!-- Grid View -->
        <div class="tasks-grid-view" id="gridView" style="display: none;">
            <div id="tasksGridContainer" class="tasks-grid">
                <!-- Tasks will be loaded here via AJAX -->
            </div>
        </div>
        
        <!-- Kanban View -->
        <div class="tasks-kanban-view" id="kanbanView" style="display: none;">
            <div class="kanban-board">
                <div class="kanban-column" data-status="Pending">
                    <div class="kanban-header">
                        <h3><i class="fas fa-clock"></i> Pending</h3>
                        <span class="task-count" id="pendingCount">0</span>
                    </div>
                    <div class="kanban-tasks" id="kanbanPending" data-status="Pending">
                        <!-- Tasks will be loaded here -->
                    </div>
                </div>
                
                <div class="kanban-column" data-status="In Progress">
                    <div class="kanban-header">
                        <h3><i class="fas fa-spinner"></i> In Progress</h3>
                        <span class="task-count" id="progressCount">0</span>
                    </div>
                    <div class="kanban-tasks" id="kanbanProgress" data-status="In Progress">
                        <!-- Tasks will be loaded here -->
                    </div>
                </div>
                
                <div class="kanban-column" data-status="Completed">
                    <div class="kanban-header">
                        <h3><i class="fas fa-check-circle"></i> Completed</h3>
                        <span class="task-count" id="completedCount">0</span>
                    </div>
                    <div class="kanban-tasks" id="kanbanCompleted" data-status="Completed">
                        <!-- Tasks will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-tasks"></i>
            <h3>No tasks found</h3>
            <p>Start by creating your first task!</p>
            <button class="btn btn-primary" id="emptyAddTaskBtn">
                <i class="fas fa-plus"></i>
                Add Task
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Task Modal -->
<div class="modal" id="taskModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Task</h3>
                <button class="modal-close" id="closeModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <input type="hidden" id="taskId" name="task_id">
                    
                    <div class="form-group">
                        <label for="taskTitle">Title <span class="required">*</span></label>
                        <input type="text" id="taskTitle" name="title" class="form-control" 
                               placeholder="Enter task title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="taskDescription">Description</label>
                        <textarea id="taskDescription" name="description" class="form-control" 
                                  rows="4" placeholder="Enter task description"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="taskDueDate">Due Date</label>
                            <input type="date" id="taskDueDate" name="due_date" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="taskDueTime">Due Time</label>
                            <input type="time" id="taskDueTime" name="due_time" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="taskPriority">Priority</label>
                            <select id="taskPriority" name="priority" class="form-control">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="taskCategory">Category</label>
                            <select id="taskCategory" name="category" class="form-control">
                                <option value="Work">Work</option>
                                <option value="Personal" selected>Personal</option>
                                <option value="Shopping">Shopping</option>
                                <option value="Health">Health</option>
                                <option value="Finance">Finance</option>
                                <option value="Education">Education</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="taskStatus">Status</label>
                            <select id="taskStatus" name="status" class="form-control">
                                <option value="Pending" selected>Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="taskProgress">Progress (%)</label>
                            <input type="number" id="taskProgress" name="progress" class="form-control" 
                                   min="0" max="100" value="0">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="taskTags">Tags</label>
                        <input type="text" id="taskTags" name="tags" class="form-control" 
                               placeholder="Enter tags separated by commas">
                        <small class="form-text">Separate multiple tags with commas</small>
                    </div>
                    
                    <div class="form-group form-check">
                        <label class="checkbox-label">
                            <input type="checkbox" id="taskFavorite" name="is_favorite" value="1">
                            <span>Mark as favorite</span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                <button type="submit" form="taskForm" class="btn btn-primary" id="saveTaskBtn">
                    <i class="fas fa-save"></i>
                    Save Task
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Delete</h3>
                <button class="modal-close" id="closeDeleteModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Pass user settings to JavaScript
window.userSettings = <?php echo json_encode($settings); ?>;
</script>

<?php include 'includes/footer.php'; ?>
