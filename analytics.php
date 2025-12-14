<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Require login
requireLogin();

// Get current user
$currentUser = getUserById($pdo, getCurrentUserId());

// Get task statistics
$stats = getTaskStatistics($pdo, getCurrentUserId());

// Get priority distribution
$sql = "SELECT priority, COUNT(*) as count FROM tasks WHERE user_id = ? GROUP BY priority";
$priorityData = fetchAll($pdo, $sql, [getCurrentUserId()]);

// Get category distribution
$sql = "SELECT category, COUNT(*) as count FROM tasks WHERE user_id = ? GROUP BY category";
$categoryData = fetchAll($pdo, $sql, [getCurrentUserId()]);

// Get status distribution
$sql = "SELECT status, COUNT(*) as count FROM tasks WHERE user_id = ? GROUP BY status";
$statusData = fetchAll($pdo, $sql, [getCurrentUserId()]);

// Get completion trend (last 7 days)
$sql = "SELECT DATE(completed_at) as date, COUNT(*) as count 
        FROM tasks 
        WHERE user_id = ? AND status = 'Completed' AND completed_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(completed_at)
        ORDER BY date ASC";
$completionTrend = fetchAll($pdo, $sql, [getCurrentUserId()]);

$pageTitle = 'Analytics';
$includeCharts = true;
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="main-container analytics-container">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Analytics & Statistics</h1>
        <div class="page-actions">
            <select id="dateRange" class="form-control">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="365">Last year</option>
            </select>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="analytics-summary">
        <div class="summary-card">
            <div class="summary-icon" style="background: #4A90E2;">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="summary-content">
                <h3><?php echo $stats['total']; ?></h3>
                <p>Total Tasks</p>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="summary-icon" style="background: #27AE60;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="summary-content">
                <h3><?php echo $stats['completed']; ?></h3>
                <p>Completed Tasks</p>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="summary-icon" style="background: #F39C12;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="summary-content">
                <h3><?php echo $stats['pending']; ?></h3>
                <p>Pending Tasks</p>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="summary-icon" style="background: #E74C3C;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="summary-content">
                <h3><?php echo $stats['overdue']; ?></h3>
                <p>Overdue Tasks</p>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="summary-icon" style="background: #9B59B6;">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="summary-content">
                <h3><?php echo $stats['completion_rate']; ?>%</h3>
                <p>Completion Rate</p>
            </div>
        </div>
    </div>
    
    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Completion Trend Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Completion Trend</h3>
            </div>
            <div class="chart-body">
                <canvas id="completionTrendChart"></canvas>
            </div>
        </div>
        
        <!-- Status Distribution Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-pie"></i> Status Distribution</h3>
            </div>
            <div class="chart-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        
        <!-- Priority Distribution Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-bar"></i> Priority Distribution</h3>
            </div>
            <div class="chart-body">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
        
        <!-- Category Distribution Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-chart-doughnut"></i> Category Distribution</h3>
            </div>
            <div class="chart-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Statistics Table -->
    <div class="stats-table-card">
        <h3><i class="fas fa-table"></i> Detailed Statistics</h3>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="fas fa-tasks"></i> Total Tasks</td>
                    <td><?php echo $stats['total']; ?></td>
                    <td>100%</td>
                </tr>
                <tr class="success-row">
                    <td><i class="fas fa-check-circle"></i> Completed</td>
                    <td><?php echo $stats['completed']; ?></td>
                    <td><?php echo $stats['completion_rate']; ?>%</td>
                </tr>
                <tr>
                    <td><i class="fas fa-spinner"></i> In Progress</td>
                    <td><?php echo $stats['in_progress']; ?></td>
                    <td><?php echo $stats['total'] > 0 ? round(($stats['in_progress'] / $stats['total']) * 100, 1) : 0; ?>%</td>
                </tr>
                <tr>
                    <td><i class="fas fa-clock"></i> Pending</td>
                    <td><?php echo $stats['pending']; ?></td>
                    <td><?php echo $stats['total'] > 0 ? round(($stats['pending'] / $stats['total']) * 100, 1) : 0; ?>%</td>
                </tr>
                <tr class="danger-row">
                    <td><i class="fas fa-exclamation-triangle"></i> Overdue</td>
                    <td><?php echo $stats['overdue']; ?></td>
                    <td><?php echo $stats['total'] > 0 ? round(($stats['overdue'] / $stats['total']) * 100, 1) : 0; ?>%</td>
                </tr>
                <tr>
                    <td><i class="fas fa-calendar-day"></i> Due Today</td>
                    <td><?php echo $stats['due_today']; ?></td>
                    <td><?php echo $stats['total'] > 0 ? round(($stats['due_today'] / $stats['total']) * 100, 1) : 0; ?>%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// Pass data to JavaScript for charts
const priorityData = <?php echo json_encode($priorityData); ?>;
const categoryData = <?php echo json_encode($categoryData); ?>;
const statusData = <?php echo json_encode($statusData); ?>;
const completionTrend = <?php echo json_encode($completionTrend); ?>;

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Completion Trend Chart
    const trendCtx = document.getElementById('completionTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: completionTrend.map(d => new Date(d.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
            datasets: [{
                label: 'Completed Tasks',
                data: completionTrend.map(d => d.count),
                borderColor: '#27AE60',
                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: statusData.map(d => d.status),
            datasets: [{
                data: statusData.map(d => d.count),
                backgroundColor: [
                    '#95A5A6', // Pending
                    '#3498DB', // In Progress
                    '#27AE60', // Completed
                    '#E74C3C'  // Cancelled
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Priority Distribution Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: priorityData.map(d => d.priority),
            datasets: [{
                label: 'Tasks',
                data: priorityData.map(d => d.count),
                backgroundColor: priorityData.map(d => {
                    switch(d.priority) {
                        case 'Critical': return '#8B0000';
                        case 'High': return '#E74C3C';
                        case 'Medium': return '#F39C12';
                        case 'Low': return '#3498DB';
                        default: return '#95A5A6';
                    }
                })
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(d => d.category),
            datasets: [{
                data: categoryData.map(d => d.count),
                backgroundColor: [
                    '#4A90E2', // Work
                    '#9B59B6', // Personal
                    '#E67E22', // Shopping
                    '#27AE60', // Health
                    '#16A085', // Finance
                    '#D35400', // Education
                    '#7F8C8D'  // Other
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
