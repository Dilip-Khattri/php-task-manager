-- PHP Task Manager Database Schema
-- Version: 1.0.0
-- Database: task_manager

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create database
CREATE DATABASE IF NOT EXISTS `task_manager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `task_manager`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'default-avatar.svg',
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `tasks`
-- --------------------------------------------------------

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL,
  `priority` enum('Critical','High','Medium','Low') DEFAULT 'Medium',
  `category` enum('Work','Personal','Shopping','Health','Finance','Education','Other') DEFAULT 'Personal',
  `status` enum('Pending','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `progress` int(11) DEFAULT 0,
  `tags` varchar(500) DEFAULT NULL,
  `is_favorite` tinyint(1) DEFAULT 0,
  `position` int(11) DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_due_date` (`due_date`),
  KEY `idx_user_status` (`user_id`, `status`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `subtasks`
-- --------------------------------------------------------

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `position` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `comments`
-- --------------------------------------------------------

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `user_settings`
-- --------------------------------------------------------

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `theme` enum('light','dark') DEFAULT 'light',
  `tasks_per_page` int(11) DEFAULT 20,
  `default_view` enum('list','grid','kanban') DEFAULT 'list',
  `notifications_enabled` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insert demo user
-- Password: demo123 (hashed with bcrypt)
-- --------------------------------------------------------

INSERT INTO `users` (`username`, `email`, `password`, `bio`) VALUES
('demo', 'demo@taskmanager.com', '$2y$10$QW7eIjwIxgkz.YDqRU.K0OP13YjjqhGEHHRGF8ct.1RTJsMa5f8u.', 'Demo user for PHP Task Manager');

-- Get the demo user ID for sample tasks
SET @demo_user_id = LAST_INSERT_ID();

-- --------------------------------------------------------
-- Insert sample tasks for demo user
-- --------------------------------------------------------

INSERT INTO `tasks` (`user_id`, `title`, `description`, `due_date`, `due_time`, `priority`, `category`, `status`, `progress`, `tags`, `is_favorite`) VALUES
(@demo_user_id, 'Complete project documentation', 'Write comprehensive documentation for the PHP Task Manager project including installation guide and user manual', DATE_ADD(CURDATE(), INTERVAL 3 DAY), '17:00:00', 'High', 'Work', 'In Progress', 60, 'documentation,work,urgent', 1),
(@demo_user_id, 'Review pull requests', 'Review and merge pending pull requests from team members', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', 'Medium', 'Work', 'Pending', 0, 'code-review,team', 0),
(@demo_user_id, 'Buy groceries', 'Get vegetables, fruits, milk, bread, and eggs from the supermarket', CURDATE(), '18:00:00', 'Low', 'Shopping', 'Pending', 0, 'shopping,personal', 0),
(@demo_user_id, 'Gym workout', 'Upper body workout - focus on chest and triceps', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '07:00:00', 'Medium', 'Health', 'Pending', 0, 'fitness,health', 1),
(@demo_user_id, 'Pay electricity bill', 'Pay monthly electricity bill before due date', DATE_ADD(CURDATE(), INTERVAL 5 DAY), '23:59:00', 'High', 'Finance', 'Pending', 0, 'bills,finance', 0),
(@demo_user_id, 'Study JavaScript ES6', 'Complete online course modules on ES6 features including promises, async/await, and arrow functions', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '20:00:00', 'Medium', 'Education', 'In Progress', 45, 'learning,javascript,programming', 1),
(@demo_user_id, 'Team meeting preparation', 'Prepare presentation slides and agenda for weekly team meeting', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '10:00:00', 'Critical', 'Work', 'Pending', 25, 'meeting,presentation,work', 1),
(@demo_user_id, 'Database backup', 'Create backup of production database and verify integrity', CURDATE(), '22:00:00', 'Critical', 'Work', 'Pending', 0, 'backup,database,maintenance', 0),
(@demo_user_id, 'Fix login bug', 'Investigate and fix the session timeout issue reported by users', DATE_SUB(CURDATE(), INTERVAL 1 DAY), '16:00:00', 'High', 'Work', 'Completed', 100, 'bug,urgent,backend', 0),
(@demo_user_id, 'Plan weekend trip', 'Research and plan weekend getaway - book hotels and create itinerary', DATE_ADD(CURDATE(), INTERVAL 10 DAY), '12:00:00', 'Low', 'Personal', 'Pending', 10, 'travel,vacation,planning', 0);

-- --------------------------------------------------------
-- Insert user settings for demo user
-- --------------------------------------------------------

INSERT INTO `user_settings` (`user_id`, `theme`, `tasks_per_page`, `default_view`, `notifications_enabled`) VALUES
(@demo_user_id, 'light', 20, 'list', 1);

-- --------------------------------------------------------
-- Insert some sample subtasks
-- --------------------------------------------------------

INSERT INTO `subtasks` (`task_id`, `title`, `is_completed`, `position`) VALUES
(1, 'Write installation instructions', 1, 1),
(1, 'Create API documentation', 0, 2),
(1, 'Add code examples', 0, 3);

INSERT INTO `subtasks` (`task_id`, `title`, `is_completed`, `position`) VALUES
(7, 'Create presentation outline', 1, 1),
(7, 'Design slides', 0, 2),
(7, 'Prepare demo', 0, 3),
(7, 'Print handouts', 0, 4);

-- --------------------------------------------------------
-- Insert sample comments
-- --------------------------------------------------------

INSERT INTO `comments` (`task_id`, `user_id`, `comment`) VALUES
(1, @demo_user_id, 'Making good progress on the documentation. Should be completed by tomorrow.'),
(7, @demo_user_id, 'Need to coordinate with the team for the agenda items.'),
(6, @demo_user_id, 'The async/await section is really helpful!');

COMMIT;
