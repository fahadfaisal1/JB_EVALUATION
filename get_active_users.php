<?php
require "conn.php";
$query = "SELECT COUNT(*) as active_count FROM datasheet 
          WHERE Date >= NOW() - INTERVAL 15 MINUTE";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode(['count' => $row['active_count']]);
?> 