/**
 * PHP Task Manager - Main JavaScript
 * Version: 1.0.0
 */

// ============================================
// Utility Functions
// ============================================

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    
    const toast = document.createElement('div');
    toast.className = `toast alert-${type}`;
    
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    toast.innerHTML = `
        <i class="fas fa-${icon}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function showLoading() {
    document.getElementById('loading-overlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
}

// ============================================
// Navigation & UI
// ============================================

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    
    if (mobileMenuToggle && navbarMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('active');
        });
    }
    
    // User dropdown toggle
    const userDropdownToggle = document.getElementById('userDropdownToggle');
    const userDropdownMenu = document.getElementById('userDropdownMenu');
    
    if (userDropdownToggle && userDropdownMenu) {
        userDropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdownMenu.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userDropdownToggle.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                userDropdownMenu.classList.remove('active');
            }
        });
    }
});

// ============================================
// Keyboard Shortcuts
// ============================================

document.addEventListener('keydown', function(e) {
    // Ctrl+N: Add new task
    if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        const addTaskBtn = document.getElementById('addTaskBtn');
        if (addTaskBtn) addTaskBtn.click();
    }
    
    // Ctrl+F: Focus search
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('taskSearch');
        if (searchInput) searchInput.focus();
    }
    
    // Ctrl+L: List view
    if (e.ctrlKey && e.key === 'l') {
        e.preventDefault();
        const listViewBtn = document.querySelector('[data-view="list"]');
        if (listViewBtn) listViewBtn.click();
    }
    
    // Ctrl+G: Grid view
    if (e.ctrlKey && e.key === 'g') {
        e.preventDefault();
        const gridViewBtn = document.querySelector('[data-view="grid"]');
        if (gridViewBtn) gridViewBtn.click();
    }
    
    // Ctrl+K: Kanban view
    if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        const kanbanViewBtn = document.querySelector('[data-view="kanban"]');
        if (kanbanViewBtn) kanbanViewBtn.click();
    }
    
    // Escape: Close modals
    if (e.key === 'Escape') {
        closeAllModals();
    }
});

// ============================================
// Modal Functions
// ============================================

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function closeAllModals() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.classList.remove('active');
    });
    document.body.style.overflow = '';
}

// Modal event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Close modal on overlay click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Close buttons
    document.querySelectorAll('.modal-close, #cancelBtn, #cancelDeleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            closeAllModals();
        });
    });
});

// ============================================
// Form Validation
// ============================================

function validateTaskForm() {
    const title = document.getElementById('taskTitle').value.trim();
    
    if (!title) {
        showToast('Task title is required', 'error');
        return false;
    }
    
    return true;
}

// ============================================
// AJAX Helper Functions
// ============================================

async function makeAjaxRequest(url, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: {}
        };
        
        if (data && method !== 'GET') {
            if (data instanceof FormData) {
                options.body = data;
            } else {
                options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
                options.body = new URLSearchParams(data);
            }
        }
        
        const response = await fetch(url, options);
        const result = await response.json();
        
        return result;
    } catch (error) {
        console.error('AJAX Error:', error);
        return { success: false, message: 'Request failed' };
    }
}

// ============================================
// Theme Switcher
// ============================================

function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', savedTheme);
}

function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    showToast(`Switched to ${newTheme} theme`);
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', initTheme);

// ============================================
// Date & Time Formatting
// ============================================

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

function formatDateTime(dateTimeString) {
    if (!dateTimeString) return '';
    const date = new Date(dateTimeString);
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('en-US', options);
}

function timeAgo(dateTimeString) {
    if (!dateTimeString) return '';
    
    const date = new Date(dateTimeString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'just now';
    if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    
    return formatDate(dateTimeString);
}

// ============================================
// Drag and Drop (Kanban)
// ============================================

let draggedElement = null;

function initDragAndDrop() {
    document.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('task-card')) {
            draggedElement = e.target;
            e.target.style.opacity = '0.5';
        }
    });
    
    document.addEventListener('dragend', function(e) {
        if (e.target.classList.contains('task-card')) {
            e.target.style.opacity = '1';
            draggedElement = null;
        }
    });
    
    document.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    
    document.addEventListener('drop', function(e) {
        e.preventDefault();
        
        if (e.target.classList.contains('kanban-tasks')) {
            const newStatus = e.target.dataset.status;
            const taskId = draggedElement?.dataset.taskId;
            
            if (taskId && newStatus) {
                updateTaskStatus(taskId, newStatus);
            }
        }
    });
}

// ============================================
// Search Debouncing
// ============================================

let searchTimeout;

function debounceSearch(callback, delay = 500) {
    return function(...args) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => callback.apply(this, args), delay);
    };
}

// ============================================
// Profile Picture Upload
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const profilePictureInput = document.getElementById('profilePicture');
    const profileAvatarPreview = document.getElementById('profileAvatarPreview');
    
    if (profilePictureInput && profileAvatarPreview) {
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    showToast('Please select a valid image file (JPG, PNG, or GIF)', 'error');
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('Image size should not exceed 5MB', 'error');
                    return;
                }
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileAvatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload image
                uploadProfilePicture(file);
            }
        });
    }
});

async function uploadProfilePicture(file) {
    const formData = new FormData();
    formData.append('profile_picture', file);
    
    showLoading();
    
    try {
        const response = await fetch('ajax/upload_profile_picture.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Profile picture updated successfully');
        } else {
            showToast(result.message || 'Failed to upload profile picture', 'error');
        }
    } catch (error) {
        showToast('Failed to upload profile picture', 'error');
    } finally {
        hideLoading();
    }
}

// ============================================
// Form Auto-save
// ============================================

function autoSaveForm(formId, saveCallback, delay = 2000) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    let saveTimeout;
    
    form.addEventListener('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            if (typeof saveCallback === 'function') {
                saveCallback(new FormData(form));
            }
        }, delay);
    });
}

// ============================================
// Initialize App
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop
    initDragAndDrop();
    
    // Set current date as default for due date inputs
    const dueDateInputs = document.querySelectorAll('input[type="date"]');
    dueDateInputs.forEach(input => {
        if (!input.value) {
            const today = new Date().toISOString().split('T')[0];
            input.min = today;
        }
    });
    
    console.log('PHP Task Manager initialized');
});
