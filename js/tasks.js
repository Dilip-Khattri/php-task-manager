/**
 * PHP Task Manager - Tasks JavaScript
 * Version: 1.0.0
 */

// ============================================
// Task Management Variables
// ============================================

let currentView = 'list';
let currentFilters = {
    status: '',
    priority: '',
    category: '',
    search: ''
};

// ============================================
// Load Tasks
// ============================================

async function loadTasks() {
    showLoading();
    
    const params = new URLSearchParams({
        view: currentView,
        ...currentFilters
    });
    
    try {
        const result = await makeAjaxRequest(`ajax/get_tasks.php?${params}`);
        
        if (result.success) {
            renderTasks(result.tasks);
            updateTaskCounts(result.tasks);
        } else {
            showToast(result.message || 'Failed to load tasks', 'error');
        }
    } catch (error) {
        showToast('Failed to load tasks', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Render Tasks
// ============================================

function renderTasks(tasks) {
    const emptyState = document.getElementById('emptyState');
    
    if (!tasks || tasks.length === 0) {
        hideAllViews();
        if (emptyState) emptyState.style.display = 'block';
        return;
    }
    
    if (emptyState) emptyState.style.display = 'none';
    
    if (currentView === 'list') {
        renderListView(tasks);
    } else if (currentView === 'grid') {
        renderGridView(tasks);
    } else if (currentView === 'kanban') {
        renderKanbanView(tasks);
    }
}

function renderListView(tasks) {
    const container = document.getElementById('tasksListContainer');
    if (!container) return;
    
    container.innerHTML = tasks.map(task => createTaskCard(task)).join('');
}

function renderGridView(tasks) {
    const container = document.getElementById('tasksGridContainer');
    if (!container) return;
    
    container.innerHTML = tasks.map(task => createTaskCard(task)).join('');
}

function renderKanbanView(tasks) {
    // Clear all kanban columns
    const pendingContainer = document.getElementById('kanbanPending');
    const progressContainer = document.getElementById('kanbanProgress');
    const completedContainer = document.getElementById('kanbanCompleted');
    
    if (!pendingContainer || !progressContainer || !completedContainer) return;
    
    pendingContainer.innerHTML = '';
    progressContainer.innerHTML = '';
    completedContainer.innerHTML = '';
    
    // Distribute tasks to columns
    tasks.forEach(task => {
        const card = createTaskCard(task, true);
        
        if (task.status === 'Pending') {
            pendingContainer.innerHTML += card;
        } else if (task.status === 'In Progress') {
            progressContainer.innerHTML += card;
        } else if (task.status === 'Completed') {
            completedContainer.innerHTML += card;
        }
    });
}

function createTaskCard(task, isKanban = false) {
    const overdueClass = task.is_overdue ? 'overdue' : '';
    const dueTodayClass = task.is_due_today ? 'due-today' : '';
    const completedClass = task.status === 'Completed' ? 'completed' : '';
    const favoriteIcon = task.is_favorite ? '<i class="fas fa-star favorite-icon"></i>' : '';
    
    const tags = task.tags ? task.tags.split(',').map(tag => 
        `<span class="badge">${tag.trim()}</span>`
    ).join('') : '';
    
    return `
        <div class="task-card ${overdueClass} ${dueTodayClass}" data-task-id="${task.id}" draggable="true">
            <div class="task-header">
                <div class="task-title-section">
                    <h3 class="task-title ${completedClass}">
                        ${favoriteIcon}
                        ${escapeHtml(task.title)}
                    </h3>
                    ${task.description ? `<p class="task-description">${escapeHtml(task.description)}</p>` : ''}
                    <div class="task-meta">
                        <span class="badge ${task.priority_class}">${task.priority}</span>
                        <span class="badge ${task.status_class}">${task.status}</span>
                        <span class="badge"><i class="fas ${task.category_icon}"></i> ${task.category}</span>
                        ${task.due_date ? `<span class="badge"><i class="fas fa-calendar"></i> ${task.formatted_due_date}</span>` : ''}
                        ${task.progress > 0 ? `<span class="badge"><i class="fas fa-chart-line"></i> ${task.progress}%</span>` : ''}
                        ${tags}
                    </div>
                </div>
                <div class="task-actions">
                    <button onclick="toggleTaskStatus(${task.id})" title="Toggle Status">
                        <i class="fas ${task.status === 'Completed' ? 'fa-undo' : 'fa-check'}"></i>
                    </button>
                    <button onclick="toggleFavorite(${task.id})" title="Toggle Favorite">
                        <i class="fas fa-star ${task.is_favorite ? 'favorite-icon' : ''}"></i>
                    </button>
                    <button onclick="editTask(${task.id})" title="Edit Task">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="duplicateTask(${task.id})" title="Duplicate Task">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button onclick="confirmDeleteTask(${task.id})" title="Delete Task">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// View Switching
// ============================================

function switchView(view) {
    currentView = view;
    
    // Update active button
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-view="${view}"]`).classList.add('active');
    
    // Show appropriate view
    hideAllViews();
    document.getElementById(`${view}View`).style.display = 'block';
    
    loadTasks();
}

function hideAllViews() {
    document.getElementById('listView').style.display = 'none';
    document.getElementById('gridView').style.display = 'none';
    document.getElementById('kanbanView').style.display = 'none';
}

// ============================================
// Filters & Search
// ============================================

function applyFilters() {
    currentFilters.status = document.getElementById('filterStatus').value;
    currentFilters.priority = document.getElementById('filterPriority').value;
    currentFilters.category = document.getElementById('filterCategory').value;
    
    loadTasks();
}

function clearFilters() {
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterPriority').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('taskSearch').value = '';
    
    currentFilters = {
        status: '',
        priority: '',
        category: '',
        search: ''
    };
    
    loadTasks();
}

function searchTasks(query) {
    currentFilters.search = query;
    loadTasks();
}

// ============================================
// Add Task
// ============================================

async function addTask() {
    if (!validateTaskForm()) return;
    
    const formData = new FormData(document.getElementById('taskForm'));
    
    showLoading();
    
    try {
        const result = await makeAjaxRequest('ajax/add_task.php', 'POST', formData);
        
        if (result.success) {
            showToast('Task created successfully');
            closeModal('taskModal');
            document.getElementById('taskForm').reset();
            loadTasks();
        } else {
            showToast(result.message || 'Failed to create task', 'error');
        }
    } catch (error) {
        showToast('Failed to create task', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Edit Task
// ============================================

async function editTask(taskId) {
    showLoading();
    
    try {
        const result = await makeAjaxRequest(`ajax/get_tasks.php`);
        
        if (result.success) {
            const task = result.tasks.find(t => t.id === taskId);
            
            if (task) {
                populateTaskForm(task);
                document.getElementById('modalTitle').textContent = 'Edit Task';
                openModal('taskModal');
            }
        }
    } catch (error) {
        showToast('Failed to load task', 'error');
    } finally {
        hideLoading();
    }
}

function populateTaskForm(task) {
    document.getElementById('taskId').value = task.id;
    document.getElementById('taskTitle').value = task.title;
    document.getElementById('taskDescription').value = task.description || '';
    document.getElementById('taskDueDate').value = task.due_date || '';
    document.getElementById('taskDueTime').value = task.due_time || '';
    document.getElementById('taskPriority').value = task.priority;
    document.getElementById('taskCategory').value = task.category;
    document.getElementById('taskStatus').value = task.status;
    document.getElementById('taskProgress').value = task.progress;
    document.getElementById('taskTags').value = task.tags || '';
    document.getElementById('taskFavorite').checked = task.is_favorite;
}

async function updateTask() {
    if (!validateTaskForm()) return;
    
    const formData = new FormData(document.getElementById('taskForm'));
    
    showLoading();
    
    try {
        const result = await makeAjaxRequest('ajax/edit_task.php', 'POST', formData);
        
        if (result.success) {
            showToast('Task updated successfully');
            closeModal('taskModal');
            loadTasks();
        } else {
            showToast(result.message || 'Failed to update task', 'error');
        }
    } catch (error) {
        showToast('Failed to update task', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Delete Task
// ============================================

let taskToDelete = null;

function confirmDeleteTask(taskId) {
    taskToDelete = taskId;
    openModal('deleteModal');
}

async function deleteTask() {
    if (!taskToDelete) return;
    
    showLoading();
    
    try {
        const result = await makeAjaxRequest('ajax/delete_task.php', 'POST', {
            task_id: taskToDelete
        });
        
        if (result.success) {
            showToast('Task deleted successfully');
            closeModal('deleteModal');
            taskToDelete = null;
            loadTasks();
        } else {
            showToast(result.message || 'Failed to delete task', 'error');
        }
    } catch (error) {
        showToast('Failed to delete task', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Toggle Task Status
// ============================================

async function toggleTaskStatus(taskId) {
    showLoading();
    
    try {
        const result = await makeAjaxRequest('ajax/toggle_task.php', 'POST', {
            task_id: taskId
        });
        
        if (result.success) {
            showToast(`Task marked as ${result.new_status}`);
            loadTasks();
        } else {
            showToast(result.message || 'Failed to update task', 'error');
        }
    } catch (error) {
        showToast('Failed to update task', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Toggle Favorite
// ============================================

async function toggleFavorite(taskId) {
    try {
        const result = await makeAjaxRequest('ajax/toggle_favorite.php', 'POST', {
            task_id: taskId
        });
        
        if (result.success) {
            showToast(result.message);
            loadTasks();
        } else {
            showToast(result.message || 'Failed to update favorite', 'error');
        }
    } catch (error) {
        showToast('Failed to update favorite', 'error');
    }
}

// ============================================
// Duplicate Task
// ============================================

async function duplicateTask(taskId) {
    showLoading();
    
    try {
        const result = await makeAjaxRequest('ajax/duplicate_task.php', 'POST', {
            task_id: taskId
        });
        
        if (result.success) {
            showToast('Task duplicated successfully');
            loadTasks();
        } else {
            showToast(result.message || 'Failed to duplicate task', 'error');
        }
    } catch (error) {
        showToast('Failed to duplicate task', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Update Task Status (Drag & Drop)
// ============================================

async function updateTaskStatus(taskId, newStatus) {
    showLoading();
    
    try {
        const result = await makeAjaxRequest('ajax/edit_task.php', 'POST', {
            task_id: taskId,
            status: newStatus
        });
        
        if (result.success) {
            showToast(`Task moved to ${newStatus}`);
            loadTasks();
        } else {
            showToast(result.message || 'Failed to update task', 'error');
        }
    } catch (error) {
        showToast('Failed to update task', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Update Task Counts
// ============================================

function updateTaskCounts(tasks) {
    const pendingCount = tasks.filter(t => t.status === 'Pending').length;
    const progressCount = tasks.filter(t => t.status === 'In Progress').length;
    const completedCount = tasks.filter(t => t.status === 'Completed').length;
    
    const pendingEl = document.getElementById('pendingCount');
    const progressEl = document.getElementById('progressCount');
    const completedEl = document.getElementById('completedCount');
    
    if (pendingEl) pendingEl.textContent = pendingCount;
    if (progressEl) progressEl.textContent = progressCount;
    if (completedEl) completedEl.textContent = completedCount;
}

// ============================================
// Event Listeners
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on dashboard page
    if (!document.getElementById('tasksListContainer')) return;
    
    // Load tasks
    loadTasks();
    
    // View switcher
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            switchView(this.dataset.view);
        });
    });
    
    // Add task button
    const addTaskBtn = document.getElementById('addTaskBtn');
    const emptyAddTaskBtn = document.getElementById('emptyAddTaskBtn');
    
    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', function() {
            document.getElementById('taskId').value = '';
            document.getElementById('taskForm').reset();
            document.getElementById('modalTitle').textContent = 'Add New Task';
            openModal('taskModal');
        });
    }
    
    if (emptyAddTaskBtn) {
        emptyAddTaskBtn.addEventListener('click', function() {
            document.getElementById('taskId').value = '';
            document.getElementById('taskForm').reset();
            document.getElementById('modalTitle').textContent = 'Add New Task';
            openModal('taskModal');
        });
    }
    
    // Task form submit
    const taskForm = document.getElementById('taskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const taskId = document.getElementById('taskId').value;
            if (taskId) {
                updateTask();
            } else {
                addTask();
            }
        });
    }
    
    // Delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', deleteTask);
    }
    
    // Filters
    const toggleFiltersBtn = document.getElementById('toggleFiltersBtn');
    const filtersPanel = document.getElementById('filtersPanel');
    
    if (toggleFiltersBtn && filtersPanel) {
        toggleFiltersBtn.addEventListener('click', function() {
            const isVisible = filtersPanel.style.display !== 'none';
            filtersPanel.style.display = isVisible ? 'none' : 'block';
        });
    }
    
    const filterInputs = ['filterStatus', 'filterPriority', 'filterCategory'];
    filterInputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', applyFilters);
        }
    });
    
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearFilters);
    }
    
    // Search
    const taskSearch = document.getElementById('taskSearch');
    if (taskSearch) {
        taskSearch.addEventListener('input', debounceSearch(function(e) {
            searchTasks(e.target.value);
        }));
    }
});
