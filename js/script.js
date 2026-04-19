// ============================================
// js/script.js - UI Interactions & Enhancements
// ============================================

// --- Dark / Light Mode Toggle ---
const themeToggle = document.getElementById('themeToggle');
const body        = document.body;

// Load saved theme preference from localStorage
function loadTheme() {
    const saved = localStorage.getItem('taskManagerTheme');
    if (saved === 'light') {
        body.classList.add('light-mode');
        if (themeToggle) themeToggle.textContent = '☀️ Light';
    } else {
        body.classList.remove('light-mode');
        if (themeToggle) themeToggle.textContent = '🌙 Dark';
    }
}

// Toggle theme and save preference
if (themeToggle) {
    themeToggle.addEventListener('click', function () {
        body.classList.toggle('light-mode');
        const isLight = body.classList.contains('light-mode');
        localStorage.setItem('taskManagerTheme', isLight ? 'light' : 'dark');
        themeToggle.textContent = isLight ? '☀️ Light' : '🌙 Dark';
    });
}

// Run on page load
loadTheme();


// --- Confirm Before Deleting a Task ---
// All delete links have class "delete-link"
document.querySelectorAll('.delete-link').forEach(function (link) {
    link.addEventListener('click', function (e) {
        // Ask user to confirm before deleting
        const confirmed = confirm('🗑️ Delete this task?\n\nThis action cannot be undone.');
        if (!confirmed) {
            e.preventDefault(); // Stop the link if user clicked "Cancel"
        }
    });
});


// --- Auto-dismiss Flash Messages ---
// Flash messages (success/error) disappear after 4 seconds
const alerts = document.querySelectorAll('.alert');
alerts.forEach(function (alert) {
    setTimeout(function () {
        alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        alert.style.opacity    = '0';
        alert.style.transform  = 'translateY(-8px)';
        // Remove from DOM after fade out
        setTimeout(() => alert.remove(), 500);
    }, 4000); // 4 seconds
});


// --- Task Name Input Validation ---
// Prevent submitting empty task name (extra client-side check)
const taskForm  = document.getElementById('taskForm');
const taskInput = document.getElementById('task_name');

if (taskForm && taskInput) {
    taskForm.addEventListener('submit', function (e) {
        const val = taskInput.value.trim();
        if (val === '') {
            e.preventDefault();
            // Highlight the empty input
            taskInput.style.borderColor = 'var(--red)';
            taskInput.style.boxShadow   = '0 0 0 3px rgba(248,113,113,0.2)';
            taskInput.focus();
            // Reset style after 2 seconds
            setTimeout(() => {
                taskInput.style.borderColor = '';
                taskInput.style.boxShadow   = '';
            }, 2000);
        }
    });
}


// --- Highlight Overdue Tasks ---
// If a task has a due date in the past and is still Pending, mark it
document.querySelectorAll('.task-date[data-due]').forEach(function (el) {
    const dueDate = new Date(el.dataset.due);
    const today   = new Date();
    today.setHours(0, 0, 0, 0); // Compare dates only (not time)

    const isPending = el.closest('.task-card') &&
                      !el.closest('.task-card').classList.contains('completed');

    if (dueDate < today && isPending) {
        el.classList.add('overdue');
        el.title = 'This task is overdue!';
    }
});
