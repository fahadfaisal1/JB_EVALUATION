<?php
session_start();
require "conn.php";

// First, check if dob column exists and add it if it doesn't
$check_dob_column = mysqli_query($con, "SHOW COLUMNS FROM users LIKE 'dob'");
if(mysqli_num_rows($check_dob_column) == 0) {
    // Add dob column with NULL allowed
    $add_dob = "ALTER TABLE users ADD COLUMN dob DATE NULL AFTER password";
    mysqli_query($con, $add_dob);
}

// Check if profile_picture column exists and add it if it doesn't
$check_pp_column = mysqli_query($con, "SHOW COLUMNS FROM users LIKE 'profile_picture'");
if(mysqli_num_rows($check_pp_column) == 0) {
    // Add profile_picture column
    $add_pp = "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL AFTER dob";
    mysqli_query($con, $add_pp);
}

// Create users table if it doesn't exist
$create_table = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    dob DATE NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

mysqli_query($con, $create_table);

if(isset($_POST['register'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    
    // Calculate age
    $today = new DateTime();
    $birthdate = new DateTime($dob);
    $age = $today->diff($birthdate)->y;
    
    if($age < 18) {
        $error = "You must be at least 18 years old to register";
    } else {
        // Check if email already exists
        $check_email = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");
        if(mysqli_num_rows($check_email) > 0) {
            $error = "Email already exists";
        } else {
            // Handle image upload
            $profile_picture = NULL;
            if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $target_dir = "uploads/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
                $new_filename = "profile_" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Check if image file is actual image
                if(getimagesize($_FILES["profile_picture"]["tmp_name"]) !== false) {
                    if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "jpeg") {
                        if(move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                            $profile_picture = $target_file;
                        }
                    }
                }
            }
            
            $query = "INSERT INTO users (username, email, password, dob, profile_picture) 
                     VALUES ('$username', '$email', '$password', '$dob', " . 
                     ($profile_picture ? "'$profile_picture'" : "NULL") . ")";
            
            if(mysqli_query($con, $query)) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Evaluation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Register</h2>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" required 
                                       max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                                <div class="form-text">You must be at least 18 years old to register.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture (Optional)</label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                                <div class="form-text">Allowed formats: JPG, JPEG, PNG</div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" name="register" class="btn" style="background-color: #4CAF50; color: white; font-weight: 500; padding: 10px; font-size: 16px;">Register</button>
                            </div>
                            
                            <p class="text-center mt-3">
                                Already have an account? 
                                <a href="login.php" style="color: #2196F3; text-decoration: none; font-weight: 500;">Login here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add client-side age validation
        document.getElementById('dob').addEventListener('change', function() {
            var dob = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            if (age < 18) {
                this.setCustomValidity('You must be at least 18 years old to register');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html> 