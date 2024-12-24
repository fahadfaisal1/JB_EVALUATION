<?php
session_start();
require "conn.php";
require "form_navbar.php";
require "notifications.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mark notification as read if requested
if(isset($_GET['mark_read'])) {
    markNotificationAsRead($con, $_GET['mark_read']);
}

// Mark all as read if requested
if(isset($_GET['mark_all_read'])) {
    markAllNotificationsAsRead($con, $_SESSION['user_id']);
    header("Location: view_notifications.php");
    exit();
}

// Get notifications
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </h3>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <a href="?mark_all_read=1" class="btn btn-sm btn-secondary">
                            Mark All as Read
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <div class="notification-list">
                            <?php while($notification = mysqli_fetch_assoc($result)): ?>
                                <div class="notification-item <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="notification-content">
                                            <h6 class="notification-title mb-1">
                                                <?php if(!$notification['is_read']): ?>
                                                    <span class="badge bg-primary me-2">New</span>
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($notification['title']); ?>
                                            </h6>
                                            <p class="notification-text mb-1">
                                                <?php echo htmlspecialchars($notification['message']); ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                <?php echo date('F j, Y g:i A', strtotime($notification['created_at'])); ?>
                                            </small>
                                        </div>
                                        <?php if(!$notification['is_read']): ?>
                                            <a href="?mark_read=<?php echo $notification['id']; ?>" 
                                               class="btn btn-sm btn-light">
                                                Mark as Read
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="far fa-bell fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No notifications found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-list {
    max-height: 600px;
    overflow-y: auto;
}

.notification-item {
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
    transition: background-color 0.2s;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: #f8f9fa;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-title {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
}

.notification-text {
    color: #4a5568;
    font-size: 0.95rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25em 0.6em;
}

.btn-light {
    background-color: #f8f9fa;
    border-color: #e2e8f0;
}

.btn-light:hover {
    background-color: #e2e8f0;
}
</style>

</body>
</html> 