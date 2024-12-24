<?php
session_start();
require "conn.php";
require"notifications.php";

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Your original form processing code
if(isset($_POST['register']))
{
    // Get old values if this is an update
    if(isset($_POST['evaluation_id'])) {
        $old_query = mysqli_query($con, "SELECT * FROM datasheet WHERE ID = " . $_POST['evaluation_id']);
        $old_values = mysqli_fetch_assoc($old_query);
    }
    
    // Validate MDA and POST_TITLE exist in their respective tables
    $MDA = intval($_POST['MDA']); // Convert to integer
    $POST_TITLE = intval($_POST['POST_TITLE']); // Convert to integer
    
    // Verify MDA exists
    $check_mda = mysqli_query($con, "SELECT ID FROM mda WHERE ID = $MDA");
    if(mysqli_num_rows($check_mda) == 0) {
        echo "<script>alert('Invalid MDA selected')</script>";
        exit();
    }
    
    // Verify POST_TITLE exists
    $check_post = mysqli_query($con, "SELECT ID FROM job_titles WHERE ID = $POST_TITLE");
    if(mysqli_num_rows($check_post) == 0) {
        echo "<script>alert('Invalid Post Title selected')</script>";
        exit();
    }

    $POST_NUMBER = 0;
    $KNOWLEDGE_TRAINING = intval($_POST['KNOWLEDGE_TRAINING']);
    $EXPERIENCE = intval($_POST['EXPERIENCE']);
    $DIVERSITY = intval($_POST['DIVERSITY']);
    $COMPLEXITY = intval($_POST['COMPLEXITY']);
    $CREATIVITY = intval($_POST['CREATIVITY']);
    $ENGAGEMENT = intval($_POST['ENGAGEMENT']);
    $NETWORK = intval($_POST['NETWORK']);
    $TEAM_ROLE_ACCOUNTABILITY = intval($_POST['TEAM_ROLE_ACCOUNTABILITY']);
    $IMPACT = intval($_POST['IMPACT']);
    $CONSEQUENCES_OF_ERROR = intval($_POST['CONSEQUENCES_OF_ERROR']);
    $PHYSICAL = intval($_POST['PHYSICAL']);
    $MENTAL_EMOTIONAL_DEMANDS = intval($_POST['MENTAL_EMOTIONAL_DEMANDS']);
    $DATE = date('Y-m-d'); // Changed to uppercase Y for 4-digit year
    $PF = "";
    $COMMENTS_REMARKS = mysqli_real_escape_string($con, $_POST['COMMENTS_REMARKS']);
    $CM_1 = mysqli_real_escape_string($con, $_POST['CM_1']);
    $CM_2 = mysqli_real_escape_string($con, $_POST['CM_2']);
    $CM_3 = mysqli_real_escape_string($con, $_POST['CM_3']);
    $CM_4 = mysqli_real_escape_string($con, $_POST['CM_4']);
    $CM_5 = mysqli_real_escape_string($con, $_POST['CM_5']);

    // Your original INSERT query
    $sql = "INSERT INTO `datasheet`(
        `MDA`,
        `Post_Title`,
        `Post_Number`,
        `Knowledge_and_Training`,
        `Experience`,
        `Diversity`,
        `Complexity`,
        `Creativity`,
        `Engagement`,
        `Networks`,
        `Teamrole_and_Accountability`,
        `Impact`,
        `Consequence_of_Error`,
        `Physical`,
        `Mental_and_Emotional_Demands`,
        `Date`,
        `Primary_Focus`,
        `Comments_Remarks`,
        `CM_1`,
        `CM_2`,
        `CM_3`,
        `CM_4`,
        `CM_5`)
        VALUES
        ($MDA,
        $POST_TITLE,
        $POST_NUMBER,
        $KNOWLEDGE_TRAINING,
        $EXPERIENCE,
        $DIVERSITY,
        $COMPLEXITY,
        $CREATIVITY,
        $ENGAGEMENT,
        $NETWORK,
        $TEAM_ROLE_ACCOUNTABILITY,
        $IMPACT,
        $CONSEQUENCES_OF_ERROR,
        $PHYSICAL,
        $MENTAL_EMOTIONAL_DEMANDS,
        '$DATE',
        '$PF',
        '$COMMENTS_REMARKS',
        '$CM_1',
        '$CM_2',
        '$CM_3',
        '$CM_4',
        '$CM_5')";

    // Execute the query and handle the result
    if(mysqli_query($con,$sql))
    {
        $datasheet_id = mysqli_insert_id($con);
        
        // Create notification for the user
        createNotification(
            $con,
            $_SESSION['user_id'],
            'Evaluation Submitted',
            'Your job evaluation for ' . $_POST['POST_TITLE'] . ' has been submitted successfully.',
            'success'
        );
        
        // Create notifications for committee members
        $committee_members = [$_POST['CM_1'], $_POST['CM_2'], $_POST['CM_3'], $_POST['CM_4'], $_POST['CM_5']];
        foreach($committee_members as $member_id) {
            createNotification(
                $con,
                $member_id,
                'New Evaluation to Review',
                'A new job evaluation requires your review.',
                'info'
            );
        }
        
        header("Location: result.php?id=$datasheet_id");
        exit();
    }
    else
    {
        echo "<script>alert('Failed: " . mysqli_error($con) . "')</script>";
    }

    // After successful save/update
    $action_type = isset($_POST['evaluation_id']) ? 'Updated Evaluation' : 'Created Evaluation';
    $datasheet_id = isset($_POST['evaluation_id']) ? $_POST['evaluation_id'] : mysqli_insert_id($con);
    
    require_once 'track_changes.php';
    logEvaluationChange(
        $con,
        $datasheet_id,
        $action_type,
        $old_values ?? null,
        $_POST
    );

    // When evaluation is updated
    createNotification(
        $con,
        $_SESSION['user_id'],
        'Evaluation Updated',
        'Job evaluation for ' . $_POST['POST_TITLE'] . ' has been updated.',
        'info'
    );

    // When score is high
    if($total > 800) {
        createNotification(
            $con,
            $_SESSION['user_id'],
            'High Score Alert',
            'The evaluation for ' . $_POST['POST_TITLE'] . ' has received a high score of ' . $total,
            'warning'
        );
    }

    // When all committee members are assigned
    createNotification(
        $con,
        $_SESSION['user_id'],
        'Committee Assignment Complete',
        'All committee members have been successfully assigned to evaluate ' . $_POST['POST_TITLE'],
        'success'
    );

    // For Admin when new evaluation is created
    $admin_query = mysqli_query($con, "SELECT id FROM users WHERE role = 'admin'");
    while($admin = mysqli_fetch_assoc($admin_query)) {
        createNotification(
            $con,
            $admin['id'],
            'New Evaluation Created',
            'A new job evaluation has been created for ' . $_POST['POST_TITLE'] . ' by ' . $_SESSION['username'],
            'info'
        );
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Form </title>

    <!-- bootstrap cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">

    </script>
    <style>
body {
    font-family: 'Inter', sans-serif;
    background-color: #f5f7fb;
    color: #2d3748;
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    padding: 2rem;
}

h1 {
    color: #1a365d;
    font-weight: 600;
    margin-bottom: 2rem;
    font-size: 2.5rem;
}

h2 {
    color: #2c5282;
    font-size: 1.5rem;
    font-weight: 500;
    margin: 2rem 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 1rem 0;
}

.card-body {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
}

select.form-control {
    cursor: pointer;
    background-image: url("data:image/svg+xml,...");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

textarea.form-control {
    min-height: 120px;
}

.btn-success {
    background-color: #48bb78;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 1rem;
}

.btn-success:hover {
    background-color: #38a169;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    h1 {
        font-size: 2rem;
    }
}

/* Section styling */
.section {
    background-color: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

/* Form row spacing */
.row {
    margin-bottom: 1rem;
}

/* Update container width and layout */
.form-container {
    max-width: 1400px;  /* Increased from 1200px */
    margin: 2rem auto;
    padding: 0 2rem;
}

/* Update grid layout for 3 columns */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Changed to 3 columns */
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Card styling updates */
.section-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    margin-bottom: 2rem;
    padding: 2rem;  /* Increased padding */
    width: 100%;
}

/* Form group updates */
.form-group {
    margin-bottom: 1.5rem;
    min-width: 0;  /* Prevents overflow */
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-container {
        padding: 1rem;
    }
}

/* Optional: Make select boxes more compact */
.form-control {
    padding: 0.5rem 0.75rem;
    font-size: 0.95rem;
}

/* Update layout structure */
.main-content {
    display: flex;
    justify-content: center;
    width: 100%;
    background-color: var(--background-color);
}
</style>
    <!-- Add Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Add jQuery before Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <?php include 'form_navbar.php'; ?>
    
<div class="container">
    <div class="text-center mt-5">
        <h1>Job Evaluation</h1>
    </div>

    <form method="POST" action="">
        <!-- First Row -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>MDA</label>
                    <select name="MDA" class="form-control select2-search" required>
                        <option value="">Select or type to search MDA...</option>
                        <?php
                        $sql = "SELECT ID, MDA FROM mda ORDER BY MDA ASC";
                        $res = mysqli_query($con, $sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . htmlspecialchars($row['MDA']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>POST TITLE</label>
                    <select name="POST_TITLE" class="form-control select2-search" required>
                        <option value="">Select or type to search Post Title...</option>
                        <?php
                        $sql = "SELECT ID, Title FROM job_titles ORDER BY Title ASC";
                        $res = mysqli_query($con, $sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . htmlspecialchars($row['Title']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Post Number</label>
                    <input type="text" name="POST_NUMBER" class="form-control" disabled>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Expertise</h2>
        <!-- Expertise Row -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Knowledge Training</label>
                    <select name="KNOWLEDGE_TRAINING" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Experience</label>
                    <select name="EXPERIENCE" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 13";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Diversity</label>
                    <select name="DIVERSITY" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Critical Thinking</h2>
        <!-- Critical Thinking Row -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Complexity</label>
                    <select name="COMPLEXITY" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Creativity</label>
                    <select name="CREATIVITY" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 16";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Communication</h2>
        <!-- Communication Row -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Engagement</label>
                    <select name="ENGAGEMENT" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 13";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Network</label>
                    <select name="NETWORK" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 13";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Service Delivery</h2>
        <!-- Service Delivery Row -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Team Role Accountability</label>
                    <select name="TEAM_ROLE_ACCOUNTABILITY" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 15";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Impact</label>
                    <select name="IMPACT" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 15";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Consequences Of Error</label>
                    <select name="CONSEQUENCES_OF_ERROR" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 13";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Working Environment</h2>
        <!-- Working Environment Row -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Physical</label>
                    <select name="PHYSICAL" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 13";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Mental Emotional Demands</label>
                    <select name="MENTAL_EMOTIONAL_DEMANDS" class="form-control" required>
                        <?php
                        $sql = "SELECT * from factor_score_level LIMIT 10";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Finetune'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Comments Row -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="form-group">
                    <label>Comments / Remarks</label>
                    <textarea class="form-control" name="COMMENTS_REMARKS" rows="3"></textarea>
                </div>
            </div>
        </div>

        <h2 class="mt-4">Committee Members</h2>
        <!-- Committee Members Row 1 -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Committee Member 1</label>
                    <select name="CM_1" class="form-control" required>
                        <?php
                        $sql = "SELECT * from committee_member";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Full_Name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Committee Member 2</label>
                    <select name="CM_2" class="form-control" required>
                        <?php
                        $sql = "SELECT * from committee_member";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Full_Name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Committee Member 3</label>
                    <select name="CM_3" class="form-control" required>
                        <?php
                        $sql = "SELECT * from committee_member";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Full_Name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Committee Members Row 2 -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Committee Member 4</label>
                    <select name="CM_4" class="form-control" required>
                        <?php
                        $sql = "SELECT * from committee_member";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Full_Name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Committee Member 5</label>
                    <select name="CM_5" class="form-control" required>
                        <?php
                        $sql = "SELECT * from committee_member";
                        $res = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($res)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['Full_Name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4 mb-5">
            <div class="col-12 text-center">
                <button type="submit" name="register" class="btn btn-success btn-lg">Submit Evaluation</button>
            </div>
        </div>
    </form>
</div>

<style>
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 8px 12px;
    }
    
    .btn-success {
        padding: 12px 40px;
        font-size: 1.1rem;
    }
    
    h2 {
        color: #2c5282;
        margin-top: 2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .mt-3 {
        margin-top: 1rem;
    }
    
    .mt-4 {
        margin-top: 1.5rem;
    }
    
    .mb-5 {
        margin-bottom: 3rem;
    }
    
    label {
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    select.form-control {
        height: calc(2.5rem + 2px);
    }
</style>

<script>
function updateActiveUsers() {
    fetch('get_active_users.php')
        .then(response => response.json())
        .then(data => {
            document.querySelector('.badge').textContent = data.count;
        });
}

// Update every 30 seconds
setInterval(updateActiveUsers, 30000);
</script>

<script>
$(document).ready(function() {
    $('.select2-search').select2({
        placeholder: "Type to search...",
        allowClear: true,
        width: '100%',
        dropdownParent: $('body'),
        minimumInputLength: 0, // Allow showing all options without typing
        language: {
            noResults: function() {
                return "No matches found";
            }
        }
    });

    // Remove the extra search field
    $('.select2-search-field').remove();
    
    // Optional: Style improvements
    $('.select2-container--default .select2-selection--single').css({
        'height': '38px',
        'padding': '4px 8px',
        'border-color': '#ced4da',
        'border-radius': '4px'
    });
});
</script>

</body>
</html>