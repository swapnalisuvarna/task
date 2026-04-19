-- ============================================
-- Student Task Manager System - Database Setup
-- ============================================

-- Step 1: Create the database
CREATE DATABASE IF NOT EXISTS task_manager;

-- Step 2: Use the database
USE task_manager;

-- Step 3: Create the tasks table
CREATE TABLE IF NOT EXISTS tasks (
    id          INT AUTO_INCREMENT PRIMARY KEY,   -- Unique ID for each task
    task_name   VARCHAR(255) NOT NULL,            -- Task title/description
    due_date    DATE DEFAULT NULL,                -- Optional due date
    status      ENUM('Pending','Completed')       -- Task status
                DEFAULT 'Pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- When task was added
);

-- Step 4: Insert some sample tasks (optional - for testing)
INSERT INTO tasks (task_name, due_date, status) VALUES
('Complete Math Assignment', '2025-06-01', 'Pending'),
('Read Chapter 5 - Physics', '2025-05-28', 'Completed'),
('Submit Project Report', '2025-06-05', 'Pending'),
('Prepare for Viva', '2025-06-10', 'Pending');
