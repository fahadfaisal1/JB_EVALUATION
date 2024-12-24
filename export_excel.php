<?php
session_start();
require 'conn.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Set headers for Excel download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Job_Evaluation_'.$id.'.csv"');
    
    // Create file pointer
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Fetch basic data
    $sql = "SELECT d.*, m.MDA as mda_name, j.Title as job_title 
            FROM datasheet d 
            LEFT JOIN mda m ON d.MDA = m.ID 
            LEFT JOIN job_titles j ON d.Post_Title = j.ID 
            WHERE d.ID = $id";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);
    
    // Basic Information
    fputcsv($output, ['Job Evaluation Report']);
    fputcsv($output, []);
    fputcsv($output, ['Basic Information']);
    fputcsv($output, ['MDA', $data['mda_name']]);
    fputcsv($output, ['Post Title', $data['job_title']]);
    fputcsv($output, ['Date', date('d/m/Y')]);
    fputcsv($output, []);

    // Factors and Scores
    fputcsv($output, ['Factor', 'Level', 'Weight', 'Score']);

    // Define factors and their weights
    $factors = [
        'Knowledge_and_Training' => ['weight' => 20],
        'Experience' => ['weight' => 15],
        'Diversity' => ['weight' => 10],
        'Complexity' => ['weight' => 10],
        'Creativity' => ['weight' => 10],
        'Engagement' => ['weight' => 5],
        'Networks' => ['weight' => 5],
        'Teamrole_and_Accountability' => ['weight' => 5],
        'Impact' => ['weight' => 10],
        'Consequence_of_Error' => ['weight' => 5],
        'Physical' => ['weight' => 2.5],
        'Mental_and_Emotional_Demands' => ['weight' => 2.5]
    ];

    $total_score = 0;
    foreach($factors as $key => $factor) {
        $level = $data[$key];
        
        // Calculate score: (Level * Weight) / 5
        $score = ($level * $factor['weight']) / 5;
        $total_score += $score;

        // Format factor name for display
        $display_name = str_replace('_', ' ', $key);
        
        fputcsv($output, [
            $display_name,
            $level,
            $factor['weight'],
            number_format($score, 2)
        ]);
    }
    
    fputcsv($output, []);
    fputcsv($output, ['Total Score', '', '', number_format($total_score, 2)]);
    fputcsv($output, []);

    // Grade based on total score
    $grade = '';
    if($total_score >= 90) $grade = 'Grade 1';
    elseif($total_score >= 80) $grade = 'Grade 2';
    elseif($total_score >= 70) $grade = 'Grade 3';
    elseif($total_score >= 60) $grade = 'Grade 4';
    elseif($total_score >= 50) $grade = 'Grade 5';
    else $grade = 'Grade 6';

    fputcsv($output, ['Grade', '', '', $grade]);
    fputcsv($output, []);

    // Committee Members
    fputcsv($output, ['Committee Members']);
    for($i = 1; $i <= 5; $i++) {
        $cm_field = 'CM_' . $i;
        if(isset($data[$cm_field]) && $data[$cm_field]) {
            $cm_query = mysqli_query($con, "SELECT username FROM users WHERE id = ".$data[$cm_field]);
            $cm = mysqli_fetch_assoc($cm_query);
            fputcsv($output, ['Committee Member '.$i, $cm['username'] ?? 'Not assigned']);
        }
    }
    
    fclose($output);
    exit;
}
?>