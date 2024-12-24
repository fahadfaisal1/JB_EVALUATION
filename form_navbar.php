<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user's profile picture
require_once "conn.php";
$user_id = $_SESSION['user_id'];
$query = "SELECT profile_picture FROM users WHERE id = $user_id";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);

require_once 'notifications.php';
$unread_count = getUnreadNotificationsCount($con, $_SESSION['user_id']);
?>

<div style="background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 60px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 1000;">
    <div style="display: flex; align-items: center;">
        <a href="index.php" style="text-decoration: none; color: #333; padding: 0 30px; font-size: 14px;">Home</a>
        <a href="form.php" style="text-decoration: none; color: #333; padding: 0 30px; font-size: 14px;">Evaluation Form</a>
        <a href="my_profile.php" style="text-decoration: none; color: #333; padding: 0 30px; font-size: 14px;">My Profile</a>
        <li class="nav-item">
            <a class="nav-link position-relative" href="view_notifications.php">
                <i class="fas fa-bell"></i>
                <?php if($unread_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $unread_count; ?>
                    </span>
                <?php endif; ?>
            </a>
        </li>
    </div>
    
    <div style="display: flex; align-items: center; margin-right: 30px;">
        <?php if(!empty($user['profile_picture']) && file_exists($user['profile_picture'])): ?>
            <a href="my_profile.php">
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                     alt="Profile" 
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 15px; cursor: pointer;">
            </a>
        <?php else: ?>
            <a href="my_profile.php" style="text-decoration: none;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: bold; color: #6c757d; cursor: pointer;">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
            </a>
        <?php endif; ?>
        <a href="logout.php" style="text-decoration: none; color: white; padding: 8px 20px; font-size: 14px; background: #f56565; border-radius: 4px;">Logout</a>
    </div>
</div>
<div style="height: 60px;"></div>