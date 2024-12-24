<?php
require "conn.php";

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';
$response = ['results' => []];

if ($type && $search) {
    $search = mysqli_real_escape_string($con, $search);
    
    if ($type === 'mda') {
        $sql = "SELECT ID as id, MDA as text 
                FROM mda 
                WHERE MDA LIKE '%$search%' 
                ORDER BY MDA ASC 
                LIMIT 20";
    } else if ($type === 'post_title') {
        $sql = "SELECT ID as id, Title as text 
                FROM job_titles 
                WHERE Title LIKE '%$search%' 
                ORDER BY Title ASC 
                LIMIT 20";
    }
    
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $response['results'][] = $row;
    }
}

echo json_encode($response);
?> 