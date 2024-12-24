<?php
session_start();
require "conn.php";
require "form_navbar.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Evaluations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> Previous Evaluations</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>MDA</th>
                            <th>Post Title</th>
                            <th>Total Score</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT d.*, m.MDA as mda_name, j.Title as job_title, 
                                (SELECT SUM(Score) FROM factor_scores WHERE Datasheet_ID = d.ID) as total_score
                                FROM datasheet d 
                                LEFT JOIN mda m ON d.MDA = m.ID 
                                LEFT JOIN job_titles j ON d.Post_Title = j.ID 
                                ORDER BY d.date DESC";
                        $result = mysqli_query($con, $sql);
                        
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . date('d M Y', strtotime($row['date'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row['mda_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                            echo "<td>" . number_format($row['total_score']) . "</td>";
                            echo "<td>
                                    <a href='result.php?id=" . $row['ID'] . "' class='btn btn-sm btn-primary'>
                                        <i class='fas fa-eye'></i> View
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0 20px rgba(0,0,0,0.08);
    border: none;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e3e8ef;
}

.card-header h3 {
    margin: 0;
    color: #2d3748;
    font-size: 1.5rem;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    color: #2d3748;
}

.table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.fas {
    margin-right: 5px;
}
</style>

</body>
</html> 