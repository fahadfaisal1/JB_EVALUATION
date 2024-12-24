<?php
session_start();
require "conn.php";
require "form_navbar.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: index.php");
    exit();
}

// Get evaluation details
$eval_query = mysqli_query($con, "SELECT ds.*, jt.Title as Post_Title, m.MDA 
                                 FROM datasheet as ds 
                                 JOIN job_titles as jt ON ds.Post_Title = jt.ID 
                                 JOIN mda as m on ds.MDA = m.ID 
                                 WHERE ds.ID = $id");
$evaluation = mysqli_fetch_assoc($eval_query);

// Get history
$history_query = mysqli_query($con, "SELECT eh.*, u.username 
                                    FROM evaluation_history eh
                                    JOIN users u ON eh.changed_by = u.id
                                    WHERE eh.datasheet_id = $id
                                    ORDER BY eh.changed_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Evaluation History</h3>
                    <p class="mb-0">Post Title: <?php echo htmlspecialchars($evaluation['Post_Title']); ?></p>
                    <p class="mb-0">MDA: <?php echo htmlspecialchars($evaluation['MDA']); ?></p>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php while($history = mysqli_fetch_assoc($history_query)): ?>
                            <div class="timeline-item">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="timeline-date">
                                            <?php echo date('F j, Y g:i A', strtotime($history['changed_at'])); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="timeline-content">
                                            <h5><?php echo htmlspecialchars($history['action_type']); ?></h5>
                                            <p>Changed by: <?php echo htmlspecialchars($history['username']); ?></p>
                                            <?php if($history['old_values'] && $history['new_values']): ?>
                                                <div class="changes-detail">
                                                    <h6>Changes:</h6>
                                                    <?php
                                                    $old = json_decode($history['old_values'], true);
                                                    $new = json_decode($history['new_values'], true);
                                                    foreach($new as $key => $value):
                                                        if($old[$key] != $value):
                                                    ?>
                                                        <div class="change-item">
                                                            <span class="field-name"><?php echo htmlspecialchars($key); ?>:</span>
                                                            <span class="old-value"><?php echo htmlspecialchars($old[$key]); ?></span>
                                                            <span class="arrow">→</span>
                                                            <span class="new-value"><?php echo htmlspecialchars($value); ?></span>
                                                        </div>
                                                    <?php 
                                                        endif;
                                                    endforeach; 
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    padding: 20px 0;
    border-left: 2px solid #e2e8f0;
    margin-left: 20px;
    position: relative;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -8px;
    top: 24px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #48bb78;
}

.timeline-date {
    color: #718096;
    font-size: 0.9rem;
}

.timeline-content {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.changes-detail {
    margin-top: 10px;
    padding: 10px;
    background: #f7fafc;
    border-radius: 4px;
}

.change-item {
    margin: 5px 0;
    font-size: 0.9rem;
}

.field-name {
    font-weight: 500;
    color: #4a5568;
}

.old-value {
    color: #e53e3e;
    text-decoration: line-through;
    margin: 0 5px;
}

.arrow {
    color: #718096;
    margin: 0 5px;
}

.new-value {
    color: #38a169;
}
</style>

</body>
</html> 