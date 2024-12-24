<?php
session_start();
require "conn.php";

// Add this right after require "conn.php";
$check_column = mysqli_query($con, "SHOW COLUMNS FROM users LIKE 'profile_picture'");
if(mysqli_num_rows($check_column) == 0) {
    // Column doesn't exist, so add it
    $add_column = "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL";
    if(mysqli_query($con, $add_column)) {
        echo "<script>console.log('Profile picture column added successfully');</script>";
    } else {
        echo "<script>console.log('Error adding profile picture column: " . mysqli_error($con) . "');</script>";
    }
}

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);    

// Handle form submission
if(isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Check if email exists for other users
    $check_email = mysqli_query($con, "SELECT id FROM users WHERE email = '$email' AND id != $user_id");
    if(mysqli_num_rows($check_email) > 0) {
        $error = "Email already exists";
    } else {
        $update_query = "UPDATE users SET username = '$username', email = '$email' WHERE id = $user_id";
        if(mysqli_query($con, $update_query)) {
            $success = "Profile updated successfully";
            // Update session username
            $_SESSION['username'] = $username;
            // Refresh user data
            $result = mysqli_query($con, $query);
            $user = mysqli_fetch_assoc($result);
        } else {
            $error = "Update failed";
        }
    }
}

// Handle password change
if(isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($new_password !== $confirm_password) {
        $password_error = "New passwords do not match";
    } else if(password_verify($current_password, $user['password'])) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_password = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
        if(mysqli_query($con, $update_password)) {
            $password_success = "Password changed successfully";
        } else {
            $password_error = "Password change failed";
        }
    } else {
        $password_error = "Current password is incorrect";
    }
}

// Handle profile picture upload
if(isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $new_filename = "profile_" . $user_id . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is actual image or fake image
    if(getimagesize($_FILES["profile_picture"]["tmp_name"]) !== false) {
        // Allow certain file formats
        if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "jpeg") {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update database with new image path
                $update_image = "UPDATE users SET profile_picture = '$target_file' WHERE id = $user_id";
                mysqli_query($con, $update_image);
                $image_success = "Profile picture updated successfully.";
                // Refresh user data
                $result = mysqli_query($con, $query);
                $user = mysqli_fetch_assoc($result);
            } else {
                $image_error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $image_error = "Sorry, only JPG, JPEG & PNG files are allowed.";
        }
    } else {
        $image_error = "File is not an image.";
    }
}

// Handle remove profile picture
if(isset($_POST['remove_picture'])) {
    if(!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
        unlink($user['profile_picture']); // Delete the file
    }
    $remove_query = "UPDATE users SET profile_picture = NULL WHERE id = $user_id";
    if(mysqli_query($con, $remove_query)) {
        $image_success = "Profile picture removed successfully";
        // Refresh user data
        $result = mysqli_query($con, $query);
        $user = mysqli_fetch_assoc($result);
    } else {
        $image_error = "Failed to remove profile picture";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Job Evaluation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="evaluation/css/cas.css" rel="stylesheet">
    <style>
        .profile-section {
            margin-bottom: 30px;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-title {
            color: #2d3748;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        .main-container {
            max-width: 1200px;
            margin: 90px auto 40px;
            padding: 0 20px;
        }
        .page-title {
            color: #2d3748;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
        }
        .top-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        @media (max-width: 768px) {
            .top-sections {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>
    
    <div class="main-container">
        <h2 class="page-title">My Profile</h2>
        
        <div class="top-sections">
            <!-- Profile Information Section (Left) -->
            <div class="profile-section">
                <h3 class="section-title">Profile Information</h3>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
            
            <!-- Profile Picture Section (Right) -->
            <div class="profile-section">
                <h3 class="section-title">Profile Picture</h3>
                
                <?php if(isset($image_success)): ?>
                    <div class="alert alert-success"><?php echo $image_success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($image_error)): ?>
                    <div class="alert alert-danger"><?php echo $image_error; ?></div>
                <?php endif; ?>
                
                <div class="text-center mb-4">
                    <?php if(!empty($user['profile_picture']) && file_exists($user['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                             alt="Profile Picture" 
                             class="rounded-circle profile-image mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <form method="POST" action="" class="mb-3">
                            <button type="submit" name="remove_picture" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure you want to remove your profile picture?')">
                                Remove Picture
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="default-avatar mb-3" style="width: 150px; height: 150px; margin: 0 auto; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 60px; color: #6c757d; font-weight: bold;">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Choose New Profile Picture</label>
                        <input type="file" class="form-control" name="profile_picture" accept="image/*" required>
                        <div class="form-text">Allowed formats: JPG, JPEG, PNG</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Picture</button>
                </form>
            </div>
        </div>
        
        <!-- Change Password Section (Bottom Center) -->
        <div class="profile-section" style="max-width: 600px; margin: 0 auto 30px;">
            <h3 class="section-title">Change Password</h3>
            
            <?php if(isset($password_success)): ?>
                <div class="alert alert-success"><?php echo $password_success; ?></div>
            <?php endif; ?>
            
            <?php if(isset($password_error)): ?>
                <div class="alert alert-danger"><?php echo $password_error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                
                <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
            </form>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="text-center mt-4">
            <a href="form.php" class="btn btn-secondary me-2">Back to Form</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    
    <!-- Add this just before </body> -->
    <script>
        // Function to hide alerts after 3 seconds
        function hideAlerts() {
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.3s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            alert.remove();
                        }, 500);
                    }, 3000);
                });
            }
        }

        // Run when page loads
        document.addEventListener('DOMContentLoaded', hideAlerts);
    </script>
</body>
</html> 