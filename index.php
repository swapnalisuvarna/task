<?php

require_once 'config/db.php';


$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter === 'pending') {
    $sql = "SELECT * FROM tasks WHERE status = 'Pending' ORDER BY created_at DESC";
} elseif ($filter === 'completed') {
    $sql = "SELECT * FROM tasks WHERE status = 'Completed' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM tasks ORDER BY created_at DESC";
}

$result = mysqli_query($conn, $sql);
$tasks  = mysqli_fetch_all($result, MYSQLI_ASSOC); // All rows as array


$total_result    = mysqli_query($conn, "SELECT COUNT(*) as c FROM tasks");
$pending_result  = mysqli_query($conn, "SELECT COUNT(*) as c FROM tasks WHERE status='Pending'");
$done_result     = mysqli_query($conn, "SELECT COUNT(*) as c FROM tasks WHERE status='Completed'");

$total   = mysqli_fetch_assoc($total_result)['c'];
$pending = mysqli_fetch_assoc($pending_result)['c'];
$done    = mysqli_fetch_assoc($done_result)['c'];


$flash_success = '';
$flash_error   = '';

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'added')   $flash_success = '✅ Task added successfully!';
    if ($_GET['success'] === 'updated') $flash_success = '✔️ Task marked as completed!';
    if ($_GET['success'] === 'deleted') $flash_success = '🗑️ Task deleted successfully!';
}
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'empty')    $flash_error = '⚠️ Task name cannot be empty.';
    if ($_GET['error'] === 'db')       $flash_error = '❌ A database error occurred. Please try again.';
    if ($_GET['error'] === 'invalid')  $flash_error = '❌ Invalid task ID.';
    if ($_GET['error'] === 'notfound') $flash_error = '❌ Task not found.';
}


$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Task Manager</title>
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="page-wrapper">

    
    <header class="site-header">
        <div class="header-left">
            <h1>📚 Task Manager</h1>
            <p>Stay on top of your studies &amp; deadlines</p>
        </div>
        <div class="header-right">
           
            <button class="theme-toggle" id="themeToggle">🌙 Dark</button>
        </div>
    </header>

   
    <?php if ($flash_success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash_success) ?></div>
    <?php endif; ?>

    <?php if ($flash_error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($flash_error) ?></div>
    <?php endif; ?>

    <!-- ==================== STATS ==================== -->
    <div class="stats-row">
        <div class="stat-card total">
            <div class="stat-number"><?= $total ?></div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-card pending">
            <div class="stat-number"><?= $pending ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card done">
            <div class="stat-number"><?= $done ?></div>
            <div class="stat-label">Done</div>
        </div>
    </div>

    <!-- ==================== ADD TASK FORM ==================== -->
    <div class="add-card">
        <h2>
            <span class="icon">✏️</span>
            Add New Task
        </h2>

        <!-- Form posts to add_task.php -->
        <form action="add_task.php" method="POST" id="taskForm">
            <div class="form-row">

                <!-- Task Name Input -->
                <div class="form-group">
                    <label for="task_name">Task Name</label>
                    <input
                        type="text"
                        id="task_name"
                        name="task_name"
                        placeholder="e.g. Complete Math assignment..."
                        maxlength="255"
                        autocomplete="off"
                        required
                    >
                </div>

                <!-- Due Date Input (optional) -->
                <div class="form-group" style="max-width:170px;">
                    <label for="due_date">Due Date <small style="opacity:0.6;">(optional)</small></label>
                    <input
                        type="date"
                        id="due_date"
                        name="due_date"
                        min="<?= date('Y-m-d') ?>"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-add">
                    <span>＋</span> Add Task
                </button>

            </div>
        </form>
    </div>

    <!-- ==================== FILTER BAR ==================== -->
    <div class="filter-bar">
        <span>Filter:</span>
        <a href="index.php?filter=all"
           class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>">
            All (<?= $total ?>)
        </a>
        <a href="index.php?filter=pending"
           class="filter-btn <?= $filter === 'pending' ? 'active' : '' ?>">
            ⏳ Pending (<?= $pending ?>)
        </a>
        <a href="index.php?filter=completed"
           class="filter-btn <?= $filter === 'completed' ? 'active' : '' ?>">
            ✅ Completed (<?= $done ?>)
        </a>
    </div>

    <!-- ==================== TASK LIST ==================== -->
    <div class="tasks-header">
        <h2>Your Tasks</h2>
        <span class="task-count-badge"><?= count($tasks) ?> shown</span>
    </div>

    <?php if (empty($tasks)): ?>
        <!-- Empty State -->
        <div class="empty-state">
            <span class="empty-icon">📭</span>
            <h3>No tasks here!</h3>
            <p>
                <?php if ($filter !== 'all'): ?>
                    No <?= $filter ?> tasks found. <a href="index.php">View all tasks</a>
                <?php else: ?>
                    Add your first task using the form above.
                <?php endif; ?>
            </p>
        </div>

    <?php else: ?>
        <div class="task-list">
            <?php foreach ($tasks as $task): ?>
                <?php
                    $is_completed = ($task['status'] === 'Completed');
                    $status_class = $is_completed ? 'completed' : 'pending';
                    $dot_class    = $is_completed ? 'completed' : 'pending';
                    $has_due      = !empty($task['due_date']);
                    $is_overdue   = $has_due && $task['due_date'] < $today && !$is_completed;
                ?>
                <div class="task-card <?= $status_class ?>">

                    <!-- Status dot indicator -->
                    <span class="task-dot <?= $dot_class ?>"></span>

                    <!-- Task Info -->
                    <div class="task-info">
                        <div class="task-name" title="<?= htmlspecialchars($task['task_name']) ?>">
                            <?= htmlspecialchars($task['task_name']) ?>
                        </div>
                        <div class="task-meta">

                            <!-- Status badge -->
                            <span class="status-badge <?= $status_class ?>">
                                <?= $is_completed ? '✔ Completed' : '⏳ Pending' ?>
                            </span>

                            <!-- Due date (if set) -->
                            <?php if ($has_due): ?>
                                <span
                                    class="task-date <?= $is_overdue ? 'overdue' : '' ?>"
                                    data-due="<?= $task['due_date'] ?>"
                                >
                                    📅 <?= date('d M Y', strtotime($task['due_date'])) ?>
                                    <?= $is_overdue ? ' ⚠️ Overdue' : '' ?>
                                </span>
                            <?php endif; ?>

                            <!-- Created at -->
                            <span class="task-date">
                                🕐 <?= date('d M', strtotime($task['created_at'])) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="task-actions">

                        <!-- Mark as Complete (only shown if pending) -->
                        <?php if (!$is_completed): ?>
                            <a
                                href="update_task.php?id=<?= $task['id'] ?>"
                                class="btn-icon complete"
                                title="Mark as Completed"
                            >✔</a>
                        <?php else: ?>
                            <!-- Placeholder so layout stays consistent -->
                            <span class="btn-icon" style="opacity:0;pointer-events:none;">✔</span>
                        <?php endif; ?>

                        <!-- Delete Task -->
                        <a
                            href="delete_task.php?id=<?= $task['id'] ?>"
                            class="btn-icon delete delete-link"
                            title="Delete Task"
                        >🗑</a>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ==================== FOOTER ==================== -->
    <footer class="site-footer">
        <p>Student Task Manager &nbsp;•&nbsp; Built with PHP + MySQL + CSS</p>
    </footer>

</div><!-- end .page-wrapper -->

<!-- Our JavaScript (loaded at end for performance) -->
<script src="js/script.js"></script>

</body>
</html>

<?php
// Close DB connection
mysqli_close($conn);
?>
