<?php
// Check if functions are already declared
if (!function_exists('createNotification')) {
    function createNotification($con, $user_id, $title, $message, $type = 'info') {
        $sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $title, $message, $type);
        return mysqli_stmt_execute($stmt);
    }
}

if (!function_exists('getUnreadNotificationsCount')) {
    function getUnreadNotificationsCount($con, $user_id) {
        $sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
}

if (!function_exists('markNotificationAsRead')) {
    function markNotificationAsRead($con, $notification_id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $notification_id);
        return mysqli_stmt_execute($stmt);
    }
}

if (!function_exists('markAllNotificationsAsRead')) {
    function markAllNotificationsAsRead($con, $user_id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        return mysqli_stmt_execute($stmt);
    }
}
?> 