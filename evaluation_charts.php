<?php
session_start();
require 'conn.php';
require 'form_navbar.php';
// Fetch last 10 evaluations with date instead of created_at
$sql = "SELECT d.*, m.MDA as mda_name, j.Title as job_title, 
        (d.Knowledge_and_Training + d.Experience + d.Diversity + d.Complexity + 
         d.Creativity + d.Engagement + d.Networks + d.Teamrole_and_Accountability + 
         d.Impact + d.Consequence_of_Error + d.Physical + d.Mental_and_Emotional_Demands) as total_score,
        DATE_FORMAT(d.date, '%d/%m/%Y') as eval_date
        FROM datasheet d 
        LEFT JOIN mda m ON d.MDA = m.ID 
        LEFT JOIN job_titles j ON d.Post_Title = j.ID 
        ORDER BY d.date DESC LIMIT 10";

$result = mysqli_query($con, $sql);
$evaluations = [];
while($row = mysqli_fetch_assoc($result)) {
    $evaluations[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Trends</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2>Evaluation Trends</h2>
    
    <!-- Score Trends Chart -->
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="scoreChart"></canvas>
        </div>
    </div>
    
    <!-- MDA Distribution Chart -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <canvas id="mdaChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Grade Distribution Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Prepare data for charts
const evaluations = <?php echo json_encode(array_reverse($evaluations)); ?>;

// Score Trends Line Chart
new Chart(document.getElementById('scoreChart'), {
    type: 'line',
    data: {
        labels: evaluations.map(e => e.eval_date),
        datasets: [{
            label: 'Evaluation Scores',
            data: evaluations.map(e => e.total_score),
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Evaluation Score Trends'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// MDA Distribution Pie Chart
const mdaCounts = evaluations.reduce((acc, curr) => {
    acc[curr.mda_name] = (acc[curr.mda_name] || 0) + 1;
    return acc;
}, {});

new Chart(document.getElementById('mdaChart'), {
    type: 'pie',
    data: {
        labels: Object.keys(mdaCounts),
        datasets: [{
            data: Object.values(mdaCounts),
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'MDA Distribution'
            }
        }
    }
});

// Grade Distribution Bar Chart
const gradeCounts = evaluations.reduce((acc, curr) => {
    const score = parseFloat(curr.total_score);
    let grade = 'Grade 6';
    if(score >= 90) grade = 'Grade 1';
    else if(score >= 80) grade = 'Grade 2';
    else if(score >= 70) grade = 'Grade 3';
    else if(score >= 60) grade = 'Grade 4';
    else if(score >= 50) grade = 'Grade 5';
    
    acc[grade] = (acc[grade] || 0) + 1;
    return acc;
}, {});

new Chart(document.getElementById('gradeChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(gradeCounts),
        datasets: [{
            label: 'Number of Evaluations',
            data: Object.values(gradeCounts),
            backgroundColor: 'rgb(75, 192, 192)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Grade Distribution'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

</body>
</html> 