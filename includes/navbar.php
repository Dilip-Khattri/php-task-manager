<?php
// Get current page
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Get current user if logged in
if (isLoggedIn()) {
    $currentUser = getUserById($pdo, getCurrentUserId());
}
?>

<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="dashboard.php" class="logo">
                <i class="fas fa-tasks"></i>
                <span><?php echo APP_NAME; ?></span>
            </a>
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="navbar-menu" id="navbarMenu">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item <?php echo $currentPage === 'analytics' ? 'active' : ''; ?>">
                    <a href="analytics.php" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="nav-item <?php echo $currentPage === 'profile' ? 'active' : ''; ?>">
                    <a href="profile.php" class="nav-link">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                    <a href="settings.php" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
            
            <div class="navbar-user">
                <div class="user-dropdown">
                    <button class="user-dropdown-toggle" id="userDropdownToggle">
                        <img src="<?php echo isset($currentUser['profile_picture']) ? 'uploads/' . escape($currentUser['profile_picture']) : 'assets/images/default-avatar.svg'; ?>" 
                             alt="Profile" class="user-avatar">
                        <span class="user-name"><?php echo isset($currentUser['username']) ? escape($currentUser['username']) : 'User'; ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <a href="profile.php" class="dropdown-item">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                        <a href="settings.php" class="dropdown-item">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
