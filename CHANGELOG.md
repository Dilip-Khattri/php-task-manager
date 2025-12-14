# Changelog

All notable changes to the PHP Task Manager project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-12-14

### Added
- **User Authentication System**
  - User registration with validation
  - Login with session management
  - Logout functionality
  - Password hashing using bcrypt
  - Remember me functionality
  - Profile management

- **Dashboard Features**
  - Statistics cards (Total, Pending, In Progress, Completed, Overdue, Due Today tasks)
  - Completion rate percentage display
  - Quick action buttons
  - View switcher (List/Grid/Kanban)
  - Advanced filters panel (Status, Priority, Category, Search)
  - Responsive design for all devices

- **Task Management (Full CRUD)**
  - Create tasks with AJAX (no page reload)
  - Read/Display tasks in multiple views
  - Update tasks inline or via modal
  - Delete tasks with confirmation dialog
  - Toggle task status (Pending ↔ Completed)
  - Duplicate tasks
  - Bulk delete functionality
  - Mark tasks as favorite
  - Real-time filter and search

- **Task Properties**
  - Title, Description
  - Due date and time
  - Priority levels (Critical, High, Medium, Low)
  - Categories (Work, Personal, Shopping, Health, Finance, Education, Other)
  - Status tracking (Pending, In Progress, Completed, Cancelled)
  - Tags support
  - Progress percentage
  - Favorite flag
  - Complete timestamp tracking

- **Advanced Features**
  - Subtasks with checkbox completion
  - Comments on tasks
  - File attachments structure
  - Activity logging
  - User settings (theme, default view, preferences)
  - Notifications system
  - Real-time search with debouncing
  - Drag and drop in Kanban view
  - Keyboard shortcuts

- **Multiple Views**
  - List View: Detailed task cards
  - Grid View: Responsive grid layout
  - Kanban View: Drag & drop columns

- **Analytics Page**
  - Task completion chart
  - Priority distribution chart
  - Category distribution chart
  - Statistics overview
  - Date range selector

- **Profile Page**
  - View/edit profile information
  - Upload profile picture
  - Change password
  - Account statistics
  - Activity timeline

- **Settings Page**
  - Theme selection (Light/Dark)
  - Default view preference
  - Notifications settings
  - Display preferences
  - Account management

- **Database Schema**
  - users table
  - tasks table
  - subtasks table
  - comments table
  - user_settings table
  - Demo user with sample data

- **Security Features**
  - SQL injection prevention (PDO prepared statements)
  - XSS prevention (htmlspecialchars)
  - Password hashing (password_hash)
  - Session security
  - Input validation (client and server side)
  - File upload validation

- **UI/UX Features**
  - Modern, clean design
  - Color-coded priorities and categories
  - Smooth animations and transitions
  - Toast notifications
  - Modal dialogs
  - Responsive design
  - Loading states
  - Empty states
  - Accessibility features

### Technical Stack
- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3, JavaScript (ES6+)
- AJAX for dynamic operations
- Chart.js for analytics
- Font Awesome for icons

### Demo Credentials
- Email: demo@taskmanager.com
- Password: demo123

[1.0.0]: https://github.com/Dilip-Khattri/php-task-manager/releases/tag/v1.0.0
