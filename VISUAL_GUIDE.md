# PHP Task Manager - Visual Guide & Screenshots

This document provides a visual overview of the PHP Task Manager application and its features.

## 🎨 Application Screenshots & UI Overview

### 1. Login Page (`index.php`)

**Features:**
- Clean, modern login interface with gradient background
- Email and password fields with validation
- "Remember me" checkbox for persistent sessions
- Demo credentials displayed prominently
- Responsive design that works on all devices

**Visual Elements:**
- Centered login box with shadow and rounded corners
- Primary blue color scheme (#4A90E2)
- Font Awesome icons for visual enhancement
- Form validation feedback
- Link to registration page

**Demo Credentials Box:**
```
Email: demo@taskmanager.com
Password: demo123
```

---

### 2. Registration Page (`register.php`)

**Features:**
- User-friendly registration form
- Real-time validation for all fields
- Password strength indicator
- Confirm password matching

**Form Fields:**
- Username (unique)
- Email address (validated)
- Password (minimum 6 characters)
- Confirm password

---

### 3. Dashboard (`dashboard.php`)

#### Statistics Cards (Top Section)
Seven colorful cards displaying:
1. **Total Tasks** - Shows total number of tasks
2. **Pending** - Tasks waiting to be started
3. **In Progress** - Tasks currently being worked on
4. **Completed** - Successfully finished tasks
5. **Overdue** - Tasks past their due date (red warning)
6. **Due Today** - Tasks due today (orange alert)
7. **Completion Rate** - Percentage of completed tasks

Each card has:
- Gradient background
- Icon representing the category
- Large number display
- Descriptive label

#### Task Controls Bar
- **View Switcher**: Toggle between List, Grid, and Kanban views
- **Search Box**: Real-time search with debouncing
- **Filters Button**: Access advanced filtering
- **Bulk Actions**: Delete multiple tasks at once

#### Filters Panel (Collapsible)
- Filter by Status (Pending, In Progress, Completed, Cancelled)
- Filter by Priority (Critical, High, Medium, Low)
- Filter by Category (Work, Personal, Shopping, Health, Finance, Education, Other)
- Clear all filters button

#### Task Views

**List View:**
- Vertical list of detailed task cards
- Each card shows:
  - Task title (with favorite star if marked)
  - Full description
  - Color-coded priority badge
  - Status badge
  - Category with icon
  - Due date (highlighted if overdue or due today)
  - Progress percentage bar
  - Tags (if any)
  - Action buttons (Toggle, Favorite, Edit, Duplicate, Delete)

**Grid View:**
- Responsive grid layout (3-4 columns on desktop, 2 on tablet, 1 on mobile)
- Compact card design
- Same information as List view in condensed format
- Visual card hover effects

**Kanban View:**
- Three columns: Pending, In Progress, Completed
- Drag and drop functionality
- Task count badges on each column
- Cards can be dragged between columns to change status
- Visual feedback during drag operations

---

### 4. Add/Edit Task Modal

**Modal Dialog Features:**
- Centered overlay with backdrop
- Close button (X) in top-right corner
- Form validation before submission

**Form Fields:**
1. **Title** (required) - Text input
2. **Description** - Textarea for detailed information
3. **Due Date** - Date picker
4. **Due Time** - Time picker
5. **Priority** - Dropdown (Critical, High, Medium, Low)
   - Critical: Dark red badge
   - High: Red badge
   - Medium: Orange badge
   - Low: Blue badge
6. **Category** - Dropdown with icons
   - Work: Briefcase icon
   - Personal: User icon
   - Shopping: Cart icon
   - Health: Heart icon
   - Finance: Dollar icon
   - Education: Graduation cap icon
   - Other: Folder icon
7. **Status** - Dropdown (Pending, In Progress, Completed, Cancelled)
8. **Progress** - Number input (0-100%)
9. **Tags** - Text input (comma-separated)
10. **Favorite** - Checkbox

**Action Buttons:**
- Cancel (closes modal)
- Save Task (submits via AJAX)

---

### 5. Analytics Page (`analytics.php`)

#### Summary Cards (Top Row)
Five summary cards with icons:
1. Total Tasks
2. Completed Tasks
3. Pending Tasks
4. Overdue Tasks
5. Completion Rate %

#### Charts Section (2x2 Grid)

**1. Completion Trend Chart (Line Chart)**
- Shows task completion over last 7 days
- X-axis: Dates
- Y-axis: Number of completed tasks
- Green line with filled area
- Smooth curve interpolation

**2. Status Distribution (Pie Chart)**
- Segments for each status
- Color-coded:
  - Pending: Gray
  - In Progress: Blue
  - Completed: Green
  - Cancelled: Red
- Percentage labels
- Legend at bottom

**3. Priority Distribution (Bar Chart)**
- Vertical bars for each priority level
- Color-matched to priority badges
- Count displayed on hover
- Y-axis shows task count

**4. Category Distribution (Doughnut Chart)**
- Circle chart with hollow center
- Each category in unique color
- Percentage distribution
- Interactive legend

#### Detailed Statistics Table
Table with columns:
- Metric name with icon
- Value (count)
- Percentage of total
- Color-coded rows for success/danger

**Date Range Selector:**
- Dropdown to change time period
- Options: Last 7 days, Last 30 days, Last 90 days, Last year

---

### 6. Profile Page (`profile.php`)

#### Left Column: Profile Card
- Large circular profile picture (120px)
  - Camera icon overlay for upload
  - Default avatar SVG if not set
- Username (large, bold)
- Email address
- Bio text area
- Member since date
- Three statistics boxes:
  - Total Tasks
  - Completed Tasks
  - Success Rate %

#### Right Column: Forms

**Edit Profile Form:**
- Username field
- Email field
- Bio textarea
- Save Changes button

**Change Password Form:**
- Current password (password field)
- New password (password field)
- Confirm new password
- Change Password button

**Recent Activity Timeline:**
- List of 10 most recent tasks
- Each showing:
  - Task title
  - Priority badge
  - Status badge
  - Time ago (e.g., "2 hours ago")
- Scrollable list

---

### 7. Settings Page (`settings.php`)

#### Appearance Settings

**Theme Selection:**
Two theme cards to choose from:
1. **Light Theme**
   - Sun icon
   - White background preview
   - Radio button selection
2. **Dark Theme**
   - Moon icon
   - Dark background preview
   - Radio button selection

Theme changes apply immediately with smooth transition

#### Display Settings

**Default View:**
- Dropdown selector
- Options: List View, Grid View, Kanban View
- Sets default view when opening dashboard

**Tasks Per Page:**
- Number input (5-100)
- Controls pagination
- Default: 20

#### Notifications

**Enable Notifications:**
- Checkbox toggle
- Enables/disables task reminders

#### Account Information Card (Read-only)
- Username
- Email
- Member Since date
- Last Updated date
- Edit Profile button (links to profile page)

#### Keyboard Shortcuts Reference Card
Table showing:
- Ctrl+N: Add new task
- Ctrl+F: Search tasks
- Ctrl+L: List view
- Ctrl+G: Grid view
- Ctrl+K: Kanban view
- Esc: Close modal

#### About Section
- Application name and version
- Brief description
- GitHub link button

---

## 🎨 Color Scheme

### Primary Colors
- **Primary Blue**: #4A90E2
- **Success Green**: #27AE60
- **Warning Orange**: #F39C12
- **Danger Red**: #E74C3C
- **Info Blue**: #3498DB

### Priority Colors
- **Critical**: #8B0000 (Dark Red)
- **High**: #E74C3C (Red)
- **Medium**: #F39C12 (Orange)
- **Low**: #3498DB (Blue)

### Category Colors
- **Work**: #4A90E2 (Blue)
- **Personal**: #9B59B6 (Purple)
- **Shopping**: #E67E22 (Orange)
- **Health**: #27AE60 (Green)
- **Finance**: #16A085 (Teal)
- **Education**: #D35400 (Dark Orange)
- **Other**: #7F8C8D (Gray)

### Theme Colors

**Light Theme:**
- Background: #F5F7FA
- Surface: #FFFFFF
- Text Primary: #2C3E50
- Text Secondary: #7F8C8D
- Border: #E0E6ED

**Dark Theme:**
- Background: #1a1a2e
- Surface: #16213e
- Text Primary: #eaeaea
- Text Secondary: #a0aec0
- Border: #2d3748

---

## 📱 Responsive Design

### Desktop (>1024px)
- Full navigation bar
- Grid view: 3-4 columns
- Kanban: 3 columns side-by-side
- Statistics: 7 cards in row

### Tablet (768px - 1024px)
- Collapsed navigation on smaller screens
- Grid view: 2 columns
- Kanban: 3 columns (narrower)
- Statistics: 4 cards per row

### Mobile (<768px)
- Hamburger menu
- Grid view: 1 column (same as list)
- Kanban: Vertical stack
- Statistics: 2 cards per row
- Full-width modals
- Touch-friendly buttons

---

## ✨ Interactive Elements

### Hover Effects
- Task cards lift slightly with shadow increase
- Buttons change color
- View switcher buttons highlight
- Chart elements show tooltips

### Animations
- Toast notifications slide in from right
- Modals fade in with backdrop
- Loading spinner rotates
- Page transitions smooth
- Theme changes animate

### Transitions
- All color changes: 0.3s ease
- Transform effects: 0.3s ease
- Opacity changes: 0.3s ease

---

## 🔔 User Feedback

### Toast Notifications
Appear in top-right corner for:
- Task created successfully (green)
- Task updated successfully (green)
- Task deleted successfully (green)
- Error messages (red)
- Status changes (blue)

Auto-dismiss after 3 seconds with fade animation

### Loading States
- Overlay with spinner for AJAX operations
- Prevents multiple submissions
- Shows when:
  - Loading tasks
  - Creating/updating tasks
  - Deleting tasks
  - Uploading files

### Empty States
When no tasks exist:
- Large icon (tasks icon, faded)
- "No tasks found" message
- Helpful suggestion
- "Add Task" button

---

## 🎯 Key Features Demonstration

### 1. Create a Task
1. Click "Add New Task" button on dashboard
2. Modal opens with empty form
3. Fill in title (required)
4. Optionally add description, dates, priority, etc.
5. Click "Save Task"
6. Toast notification confirms creation
7. Modal closes
8. New task appears in current view

### 2. Edit a Task
1. Click edit icon on any task card
2. Modal opens with pre-filled form
3. Modify any fields
4. Click "Save Task"
5. Toast notification confirms update
6. Task card updates in real-time

### 3. Toggle Task Completion
1. Click checkmark icon on task
2. Task status changes (Pending ↔ Completed)
3. Toast shows new status
4. Task appearance updates (strikethrough if completed)
5. Statistics cards update

### 4. Filter Tasks
1. Click "Filters" button
2. Panel expands below
3. Select status, priority, or category
4. Tasks filter in real-time
5. Click "Clear Filters" to reset

### 5. Search Tasks
1. Type in search box
2. After 500ms delay (debounce)
3. Tasks filter to match query
4. Searches in title, description, and tags

### 6. Switch Views
1. Click List/Grid/Kanban button
2. View changes instantly
3. Tasks reorganize
4. Selection persists across sessions

### 7. Drag and Drop (Kanban)
1. Switch to Kanban view
2. Click and hold task card
3. Drag to different column
4. Drop to change status
5. AJAX updates database
6. Toast confirms change

### 8. Change Theme
1. Go to Settings page
2. Click Light or Dark theme card
3. Theme changes immediately
4. All colors transition smoothly
5. Selection saved to database
6. Persists across sessions

---

## 🔒 Security Features in Action

### Input Validation
- Client-side: Immediate feedback on forms
- Server-side: Validation before database
- Email format checked
- Password length enforced
- XSS prevention on all output

### Authentication
- Sessions validated on every protected page
- Password hashed with bcrypt (cost 10)
- Remember me uses secure tokens
- Automatic redirect if not logged in

### Database Security
- All queries use PDO prepared statements
- No raw SQL with user input
- SQL injection prevented
- Input sanitized before storage

---

## 📊 Database Structure (Visual)

```
users
├── id (PK)
├── username (UNIQUE)
├── email (UNIQUE)
├── password (HASHED)
├── profile_picture
├── bio
├── created_at
└── updated_at

tasks
├── id (PK)
├── user_id (FK → users.id)
├── title
├── description
├── due_date
├── due_time
├── priority (ENUM)
├── category (ENUM)
├── status (ENUM)
├── progress (0-100)
├── tags
├── is_favorite
├── position
├── completed_at
├── created_at
└── updated_at

subtasks
├── id (PK)
├── task_id (FK → tasks.id)
├── title
├── is_completed
├── position
└── created_at

comments
├── id (PK)
├── task_id (FK → tasks.id)
├── user_id (FK → users.id)
├── comment
├── created_at
└── updated_at

user_settings
├── id (PK)
├── user_id (FK → users.id, UNIQUE)
├── theme (ENUM: light/dark)
├── tasks_per_page
├── default_view (ENUM)
└── notifications_enabled
```

---

## 🚀 Getting Started Guide

### Step 1: Initial Setup
1. Import `database/task_manager.sql` to MySQL
2. Copy `config/database.example.php` to `config/database.php`
3. Update database credentials
4. Set file permissions on `uploads/` directory
5. Access application via web browser

### Step 2: First Login
1. Navigate to the application URL
2. Use demo credentials:
   - Email: `demo@taskmanager.com`
   - Password: `demo123`
3. Dashboard loads with 10 sample tasks

### Step 3: Explore Features
1. View statistics on dashboard
2. Try different view modes
3. Create a new task
4. Toggle task completion
5. Check analytics page
6. Update your profile
7. Change theme in settings

---

## 💡 Tips & Tricks

1. **Keyboard Shortcuts**: Use Ctrl+N to quickly add tasks
2. **Favorite Tasks**: Star important tasks to keep them at top
3. **Bulk Operations**: Select multiple tasks for batch delete
4. **Smart Search**: Search across title, description, and tags
5. **Drag & Drop**: Use Kanban view for visual workflow
6. **Dark Mode**: Enable in Settings for low-light environments
7. **Progress Tracking**: Update progress % as you work
8. **Tags**: Use comma-separated tags for better organization

---

This visual guide demonstrates the complete functionality of the PHP Task Manager application. For actual usage, please set up the application following the installation guide in INSTALL.md.
