# PHP Task Manager

A modern, full-featured task management web application built with PHP, MySQL, HTML, CSS, and JavaScript. This production-ready application demonstrates professional full-stack development skills with a clean, responsive interface and comprehensive functionality.

## 🌟 Features

### Core Features
- **User Authentication System**
  - Secure user registration with validation
  - Login with session management
  - Password hashing using bcrypt
  - Remember me functionality
  - Profile management

- **Comprehensive Dashboard**
  - Real-time statistics cards (Total, Pending, In Progress, Completed, Overdue, Due Today tasks)
  - Completion rate percentage tracking
  - Quick action buttons for common tasks
  - Multiple view options (List, Grid, Kanban)
  - Advanced filtering panel (Status, Priority, Category)
  - Real-time search with debouncing

- **Full CRUD Task Management**
  - **Create**: Add tasks with AJAX (no page reload)
  - **Read**: Display tasks in multiple customizable views
  - **Update**: Edit tasks inline or via modal dialogs
  - **Delete**: Delete with confirmation dialogs
  - Toggle task status (Pending ↔ Completed)
  - Duplicate tasks
  - Bulk delete operations
  - Mark tasks as favorites
  - Advanced filter and search

- **Rich Task Properties**
  - Title and description
  - Due date and time
  - Priority levels (Critical, High, Medium, Low)
  - Categories (Work, Personal, Shopping, Health, Finance, Education, Other)
  - Status tracking (Pending, In Progress, Completed, Cancelled)
  - Tags support
  - Progress percentage
  - Favorite flag
  - Complete timestamp tracking

- **Multiple Views**
  - **List View**: Detailed task cards in vertical layout
  - **Grid View**: Responsive grid layout (3-4 columns)
  - **Kanban View**: Drag-and-drop columns for different statuses

- **Analytics Dashboard**
  - Task completion trend chart (7-day view)
  - Status distribution pie chart
  - Priority distribution bar chart
  - Category distribution doughnut chart
  - Comprehensive statistics overview
  - Date range selector

- **User Profile**
  - View and edit profile information
  - Profile picture upload support
  - Change password functionality
  - Account statistics display
  - Recent activity timeline

- **Settings Page**
  - Theme selection (Light/Dark mode)
  - Default view preference
  - Tasks per page configuration
  - Notifications settings
  - Keyboard shortcuts reference

### Advanced Features
- Color-coded priorities and categories
- Smooth animations and transitions
- Toast notifications for user feedback
- Modal dialogs for forms
- Fully responsive design (mobile, tablet, desktop)
- Loading states and spinners
- Empty states with helpful messages
- Keyboard shortcuts support
- Drag and drop in Kanban view
- Real-time search with debouncing

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **AJAX**: Fetch API for dynamic operations
- **Charts**: Chart.js for analytics visualization
- **Icons**: Font Awesome 6.4.0
- **Architecture**: MVC-inspired structure with separation of concerns

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Modern web browser (Chrome, Firefox, Safari, Edge)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Dilip-Khattri/php-task-manager.git
cd php-task-manager
```

### 2. Database Setup

1. Create a MySQL database:
   ```sql
   CREATE DATABASE task_manager;
   ```

2. Import the database schema:
   ```bash
   mysql -u your_username -p task_manager < database/task_manager.sql
   ```
   
   Or using phpMyAdmin:
   - Open phpMyAdmin
   - Select the `task_manager` database
   - Go to the Import tab
   - Choose `database/task_manager.sql`
   - Click Go

### 3. Configure Database Connection

Edit `config/database.php` and update your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'task_manager');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 4. Configure Application

Edit `config/config.php` to set your application URL:

```php
define('APP_URL', 'http://localhost/php-task-manager');
```

### 5. Set Permissions

Ensure the `uploads` directory is writable:

```bash
chmod 755 uploads/
```

### 6. Access the Application

Open your web browser and navigate to:
```
http://localhost/php-task-manager
```

## 🔐 Demo Credentials

The application comes with a pre-configured demo account:

- **Email**: demo@taskmanager.com
- **Password**: demo123

The demo account includes 10 sample tasks to help you explore the features.

## 📖 Usage Guide

### Getting Started

1. **Login/Register**
   - Use the demo credentials or create a new account
   - Registration requires username, email, and password (minimum 6 characters)

2. **Dashboard Overview**
   - View statistics cards showing your task metrics
   - Use the view switcher to change between List, Grid, and Kanban views
   - Apply filters to find specific tasks
   - Use the search bar for quick task lookup

3. **Creating Tasks**
   - Click the "Add New Task" button
   - Fill in the task details (title is required)
   - Set due date, priority, category, and other properties
   - Click "Save Task" to create

4. **Managing Tasks**
   - Click the edit icon to modify a task
   - Click the checkmark to toggle task completion
   - Click the star to mark/unmark as favorite
   - Click the duplicate icon to create a copy
   - Click the trash icon to delete (with confirmation)

5. **Using Views**
   - **List View**: Best for detailed task information
   - **Grid View**: Ideal for visual overview
   - **Kanban View**: Perfect for workflow management with drag-and-drop

6. **Filtering & Search**
   - Use the filters panel to narrow down tasks
   - Filter by status, priority, or category
   - Search by title, description, or tags
   - Clear all filters with one click

7. **Analytics**
   - Navigate to Analytics page
   - View charts showing task distribution
   - Track completion trends over time
   - Review detailed statistics table

8. **Profile Management**
   - Update your username, email, and bio
   - Upload a profile picture
   - Change your password
   - View your account statistics

9. **Settings**
   - Choose between Light and Dark themes
   - Set your preferred default view
   - Configure tasks per page
   - Enable/disable notifications

### Keyboard Shortcuts

- `Ctrl + N`: Add new task
- `Ctrl + F`: Focus search
- `Ctrl + L`: Switch to list view
- `Ctrl + G`: Switch to grid view
- `Ctrl + K`: Switch to kanban view
- `Esc`: Close modal/dialog

## 📁 File Structure

```
php-task-manager/
├── index.php                 # Login page
├── register.php              # User registration
├── dashboard.php             # Main task management interface
├── profile.php               # User profile page
├── settings.php              # User settings
├── analytics.php             # Analytics and charts
├── logout.php                # Logout handler
├── config/
│   ├── config.php           # Application configuration
│   └── database.php         # Database connection
├── includes/
│   ├── header.php           # Common header
│   ├── footer.php           # Common footer
│   ├── navbar.php           # Navigation bar
│   └── functions.php        # Helper functions
├── ajax/
│   ├── add_task.php         # Create task
│   ├── edit_task.php        # Update task
│   ├── delete_task.php      # Delete task
│   ├── toggle_task.php      # Toggle task status
│   ├── get_tasks.php        # Fetch tasks
│   ├── duplicate_task.php   # Duplicate task
│   ├── toggle_favorite.php  # Toggle favorite
│   └── bulk_delete.php      # Bulk delete
├── css/
│   ├── style.css            # Main stylesheet
│   └── themes.css           # Theme support
├── js/
│   ├── script.js            # Main JavaScript
│   └── tasks.js             # Task-specific JavaScript
├── assets/
│   └── images/              # Image assets
├── uploads/                 # User uploads
├── database/
│   └── task_manager.sql     # Database schema
├── README.md                # This file
├── CHANGELOG.md             # Version history
├── LICENSE                  # MIT License
└── .gitignore              # Git ignore rules
```

## 🔒 Security Features

- **SQL Injection Prevention**: PDO prepared statements throughout
- **XSS Prevention**: `htmlspecialchars()` for all output
- **Password Security**: Bcrypt hashing with `password_hash()`
- **Session Security**: Proper session management and validation
- **Input Validation**: Both client-side and server-side validation
- **CSRF Protection**: Form token validation (recommended for production)
- **File Upload Validation**: Type and size restrictions

## 🎨 Customization

### Changing Colors

Edit `css/style.css` and modify the CSS variables in the `:root` section:

```css
:root {
    --primary: #4A90E2;
    --success: #27AE60;
    --warning: #F39C12;
    --danger: #E74C3C;
    /* ... other colors ... */
}
```

### Adding Custom Categories

Update the category enum in `database/task_manager.sql` and modify the category arrays in:
- `ajax/add_task.php`
- `ajax/edit_task.php`
- `includes/functions.php`
- Form selects in `dashboard.php`

## 🐛 Troubleshooting

### Database Connection Failed
- Verify database credentials in `config/database.php`
- Ensure MySQL service is running
- Check database user permissions

### Tasks Not Loading
- Check browser console for JavaScript errors
- Verify AJAX endpoints are accessible
- Ensure database connection is working

### File Upload Issues
- Check `uploads/` directory permissions
- Verify `upload_max_filesize` in php.ini
- Ensure sufficient disk space

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Dilip Khattri**
- GitHub: [@Dilip-Khattri](https://github.com/Dilip-Khattri)

## 🙏 Acknowledgments

- Font Awesome for icons
- Chart.js for analytics charts
- The PHP and MySQL communities

## 📞 Support

For issues, questions, or contributions, please open an issue on the GitHub repository.

---

**Version**: 1.0.0  
**Last Updated**: December 2025