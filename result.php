<?php
ob_start();
session_start();
require "conn.php";
require "form_navbar.php";

$id = $_GET['id'];

$com_date= mysqli_query($con,"SELECT ds.date,jt.Title as Post_Title,m.MDA from datasheet as ds JOIN job_titles as jt ON ds.Post_Title = jt.ID JOIN mda as m on ds.MDA=m.ID where ds.ID = $id");
$res_11 = mysqli_fetch_array($com_date);


$com_KNOW= mysqli_query($con, "SELECT d.ID,fsl.Finetune AS Knowledge_and_Training,
fsl.ID AS fsl_id FROM datasheet as d inner JOIN factor_score_level as fsl ON 
d.Knowledge_and_Training = fsl.ID where d.ID = $id");

$com_Experience = mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Experience,fsl.ID AS fsl_id FROM datasheet as d inner JOIN factor_score_level as fsl ON d.Experience = fsl.ID where d.ID = $id");

$com_Diversity= mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Diversity,fsl.ID AS fsl_id FROM datasheet as d inner 
JOIN factor_score_level as fsl ON d.Diversity = fsl.ID where d.ID = $id");

$com_Complexity= mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Complexity,fsl.ID AS fsl_id  FROM datasheet as d inner 
JOIN factor_score_level as fsl ON d.Complexity = fsl.ID  where d.ID = $id");

$com_Creativity =mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Creativity,fsl.ID AS fsl_id  FROM datasheet as d inner JOIN 
factor_score_level as fsl ON d.Creativity = fsl.ID  where d.ID = $id");

$com_Engagement= mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Engagement,fsl.ID AS fsl_id  FROM datasheet as d inner JOIN 
factor_score_level as fsl ON d.Engagement = fsl.ID  where d.ID = $id");

$com_Networks=mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Networks,fsl.ID AS fsl_id  FROM datasheet as d inner JOIN 
factor_score_level as fsl ON d.Networks = fsl.ID  where d.ID = $id");

$com_Teamrole_and_Accountability= mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Teamrole_and_Accountability,fsl.ID AS fsl_id  FROM datasheet as 
d inner JOIN factor_score_level as fsl ON d.Teamrole_and_Accountability = fsl.ID  where d.ID = $id");

$com_Impact= mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Impact,fsl.ID AS fsl_id  FROM datasheet as d inner JOIN factor_score_level 
as fsl ON d.Impact = fsl.ID where d.ID = $id");


$con_Consequence_of_Error=mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Consequence_of_Error ,fsl.ID AS fsl_id FROM datasheet as d inner 
JOIN factor_score_level as fsl ON d.Consequence_of_Error = fsl.ID where d.ID = $id");
 

$con_Physical = mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Physical,fsl.ID AS fsl_id  FROM datasheet as d inner JOIN 
factor_score_level as fsl ON d.Physical = fsl.ID where d.ID = $id;");

$con_Mental_and_Emotional_Demands= mysqli_query($con,"SELECT d.ID,fsl.Finetune AS Mental_and_Emotional_Demands,fsl.ID AS fsl_id FROM datasheet 
as d inner JOIN factor_score_level as fsl ON d.Mental_and_Emotional_Demands = fsl.ID where d.ID = $id;");

$con_CM_1= mysqli_query($con,"SELECT d.ID,cm.Full_Name AS CM_1 FROM datasheet as d inner JOIN committee_member as 
cm ON d.CM_1 = cm.ID where d.ID = $id;
"); 

$con_CM_2= mysqli_query($con,"SELECT d.ID,cm.Full_Name AS CM_2 FROM datasheet as d inner JOIN committee_member as 
cm ON d.CM_2 = cm.ID where d.ID = $id;
"); 
$con_CM_3= mysqli_query($con,"SELECT d.ID,cm.Full_Name AS CM_3 FROM datasheet as d inner JOIN committee_member as 
cm ON d.CM_3 = cm.ID where d.ID = $id;
");
$con_CM_4= mysqli_query($con,"SELECT d.ID,cm.Full_Name AS CM_4 FROM datasheet as d inner JOIN committee_member as 
cm ON d.CM_4 = cm.ID where d.ID = $id;
"); 
$con_CM_5= mysqli_query($con,"SELECT d.ID,cm.Full_Name AS CM_5 FROM datasheet as d inner JOIN committee_member as 
cm ON d.CM_5 = cm.ID where d.ID = $id;
");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result - Job Evaluation</title>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Classification Evaluation</title>
          
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400" rel="stylesheet" type="text/css">

<!--   CSS for 147 Colors   -->
<link href="http://www.colorname.xyz/style.css" rel="stylesheet" type="text/css"> 
   
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css"> 

    <!-- bootstrap cdn -->
    /

<!-- external css -->
<link rel="stylesheet" href="./style.css">

<style>
.inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.tableizer-table {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.tableizer-table th, 
.tableizer-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
}

.tableizer-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-align: left;
}

.tableizer-firstrow {
    background-color: #f8f9fa;
}

.tableizer-table h2 {
    margin: 0;
    color: #2c5282;
    font-size: 1.1rem;
}

/* Match container width with top section */
.container-fluid.inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Ensure consistent width for all sections */
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Make table cells more spacious */
.tableizer-table td {
    padding: 15px;
    vertical-align: middle;
}

/* Add some hover effect */
.tableizer-table tr:hover {
    background-color: #f8f9fa;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.tableizer-table {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.tableizer-table th, 
.tableizer-table td {
    padding: 15px;
    border: 1px solid #ddd;
}

.tableizer-firstrow th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.res {
    width: 100%;
    margin: 20px 0;
}

.res p {
    margin: 10px 0;
    padding: 10px;
    background-color: white;
}

.committee-member {
    padding: 15px;
    background: #fff;
    border-radius: 5px;
    margin-bottom: 15px;
}

.committee-member h5 {
    margin: 0;
    color: #2d3748;
    font-size: 1rem;
}
</style>
</head>
          
<h1 class="ce">Classification Evaluation</h1>

<!-- Add this right after your <h1 class="ce">Classification Evaluation</h1> -->
<div style="text-align: right; margin-right: 20px; margin-top: -50px;">
    <form method="POST" action="">
        <button type="submit" name="export_pdf" class="btn" 
                style="background-color: #e74c3c; color: white; font-weight: 500; padding: 10px 20px; font-size: 16px;">
            <i class="fas fa-file-pdf"></i> Export as PDF
        </button>
    </form>
</div>

<div class="container">
    <div class="res">
<!-- section -->
<p>Post No:
<hr>
<p>Post Title:&nbsp&nbsp&nbsp&nbsp&nbsp<?=$res_11['Post_Title'];?>
<hr>
<p>Ministry Department/Division/Unit:&nbsp&nbsp&nbsp&nbsp&nbsp<?=$res_11['MDA'];?>
<hr>
<p>Current Level:
<hr>
<p>Level Accorded:
<hr>
<p>Date:&nbsp&nbsp&nbsp&nbsp&nbsp<?=$res_11['date'];?>
<hr>
<p>Primary Focus: Reclassification
   </div>
<!--  section-->

</div>

<?php

$row_1=    mysqli_fetch_array($com_KNOW);
$row_2=    mysqli_fetch_array($com_Experience);
$row_3=    mysqli_fetch_array($com_Diversity);
$row_4=    mysqli_fetch_array($com_Complexity);
$row_5=    mysqli_fetch_array($com_Creativity);
$row_6=    mysqli_fetch_array($com_Engagement);
$row_7=    mysqli_fetch_array($com_Networks);
$row_8=    mysqli_fetch_array($com_Teamrole_and_Accountability);
$row_9=    mysqli_fetch_array($com_Impact);
$row_10=   mysqli_fetch_array($con_Consequence_of_Error);
$row_11=   mysqli_fetch_array($con_Physical);
$row_12=   mysqli_fetch_array($con_Mental_and_Emotional_Demands);
$row_13=   mysqli_fetch_array($con_CM_1);
$row_14=   mysqli_fetch_array($con_CM_2);
$row_15=   mysqli_fetch_array($con_CM_3);
$row_16=   mysqli_fetch_array($con_CM_4);
$row_17=   mysqli_fetch_array($con_CM_5);
$row_date= mysqli_fetch_array($com_date);


// Scores 

// Knowledge
$k_f_id = $row_1['fsl_id'];
$score_know = mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 1 AND s.FacID = 1 AND s.LevelID = $k_f_id");
$row_s_know = mysqli_fetch_array($score_know);

// Expertise
$e_f_id = $row_2['fsl_id'];
$score_exp= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 1 AND s.FacID = 2 AND s.LevelID = $e_f_id");
$row_s_exp = mysqli_fetch_array($score_exp);

// Diversity
$d_f_id = $row_3['fsl_id'];
$score_diversity= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 1 AND s.FacID = 3 AND s.LevelID = $d_f_id");
$row_s_div = mysqli_fetch_array($score_diversity);

// Complexity
$c_f_id = $row_4['fsl_id'];
$score_complexity= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 2 AND s.FacID = 4 AND s.LevelID = $c_f_id");
$row_s_complexity = mysqli_fetch_array($score_complexity);

// Creativity
$cr_f_id = $row_5['fsl_id'];
$score_creativity= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 2 AND s.FacID = 5 AND s.LevelID = $cr_f_id");
$row_s_creativity = mysqli_fetch_array($score_creativity);

// Engagements
$en_f_id = $row_6['fsl_id'];
$score_engagement= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 3 AND s.FacID = 6 AND s.LevelID = $en_f_id");
$row_s_engagement = mysqli_fetch_array($score_engagement);

// Networks
$n_f_id = $row_7['fsl_id'];
$score_networks= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 3 AND s.FacID = 7 AND s.LevelID = $n_f_id");
$row_s_networks = mysqli_fetch_array($score_networks);

// Team Role And Accountability
$tr_f_id = $row_8['fsl_id'];
$score_teamrole= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 4 AND s.FacID = 8 AND s.LevelID = $tr_f_id");
$row_s_teamrole = mysqli_fetch_array($score_teamrole);

// Impact
$im_f_id = $row_9['fsl_id'];
$score_impact= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 4 AND s.FacID = 9 AND s.LevelID = $im_f_id");
$row_s_impact = mysqli_fetch_array($score_impact);

// Concequences of error
$ce_f_id = $row_10['fsl_id'];
$score_ce= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 4 AND s.FacID = 10 AND s.LevelID = $ce_f_id");
$row_s_ce = mysqli_fetch_array($score_ce);

// Physical
$p_f_id = $row_11['fsl_id'];
$score_physical= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 5 AND s.FacID = 11 AND s.LevelID = $p_f_id");
$row_s_physical = mysqli_fetch_array($score_physical);

// Mental
$m_f_id = $row_12['fsl_id'];
$score_mental= mysqli_query($con,"SELECT s.score FROM scores as s WHERE s.CatID = 5 AND s.FacID = 12 AND s.LevelID = $m_f_id");
$row_s_mental = mysqli_fetch_array($score_mental);

// Calculations
// Expertise
$total_1=$row_s_know['score'] + $row_s_exp['score'] + $row_s_div['score'];
// Critical Thinking
$total_2=$row_s_complexity['score'] + $row_s_creativity['score'];
// Communication
$total_3=$row_s_networks['score'] + $row_s_engagement['score'];
// Service Delivery
$total_4 = $row_s_teamrole['score'] + $row_s_impact['score'] + $row_s_ce['score'];
// Working Condition
$total_5 = $row_s_physical['score'] + $row_s_mental['score'];

// Total
$total = $total_1 + $total_2 + $total_3 + $total_4 + $total_5;

?>
<div class="container">
    <table class="tableizer-table">
        <tr class="tableizer-firstrow">
            <th>Factor</th>
            <th>Subfactor</th>
            <th>Level</th>
            <th>Score</th>
        </tr>
        <tr>
        <td><h2>Expertise</h2></td>
            <td>Knowledge And training <hr>Experience <hr>Diversity</td>
            <td>
                
                    <?php echo $row_1['Knowledge_and_Training']; ?>
                <hr><?php echo $row_2['Experience'];?>
                <hr><?php echo $row_3['Diversity'];?>
            </td>
            <td><?=$total_1?></td>
        </tr>
        <!-- critical Thining -->
        <tr>
            <td><h2>Critical Thinking</h2></td>
            <td>Complexity<hr>Creativity</td>
            <td><?php echo $row_4['Complexity']; ?>
            <hr><?php echo $row_5['Creativity']; ?></td>
            <td><?=$total_2?></td>
        </tr>
        <!-- critical Thining -->
        <!-- Communication -->
        <tr>
            <td><h2>Communication</h2></td>
            <td>Engagements<hr>Networks</td>
            <td><?php echo $row_6['Engagement']; ?>
            <hr><?php echo $row_7['Networks']; ?></td>
            <td><?=$total_3?></td>
        </tr>
        <!-- Communication-->
        <!-- Service Delivery-->
        <tr>
            <td><h2>Service Delivery</h2></td>
            <td>Team Role And Accountability<hr>Impact<hr>Consequences Of Error</td>
            <td><?php echo $row_8['Teamrole_and_Accountability']; ?>
            <hr><?php echo $row_9['Impact']; ?>
            <hr><?php echo $row_10['Consequence_of_Error']; ?></td>
            <td><?=$total_4?></td>
        </tr>
        <!-- Service Delivery-->
        <!-- Working Conditions-->
        <tr>
            <td><h2>Working Conditions</h2></td>
            <td>Physical<hr>Mental</td>

            <td><?php echo $row_11['Physical']; ?>
            <hr><?php echo $row_12['Mental_and_Emotional_Demands']; ?></td>
            
            <td><?=$total_5?></td>
        </tr>
        <!-- Working Conditions-->
        <!-- Score-->
        <tr>
            <td><h2>Total Score</h2></td>
            <td></td>
            <td></td>
            <td><?=$total?></td>
        </tr>
        <!-- Score-->
    </table>
</div>

<div class="container">
    <div class="row">
        <!-- First Row: 3 members -->
        <div class="col-md-4">
            <div class="committee-member">
                <p>Committee Member: <?php echo $row_13['CM_1']; ?></h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="committee-member">
                <p>Committee Member: <?php echo $row_14['CM_2']; ?></h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="committee-member">
                <p>Committee Member: <?php echo $row_15['CM_3']; ?></h5>
            </div>
        </div>
    </div>

    <!-- Second Row: 2 members -->
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="committee-member">
                <p>Committee Member: <?php echo $row_16['CM_4']; ?></h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="committee-member">
                <p>Committee Member: <?php echo $row_17['CM_5']; ?></h5>
            </div>
        </div>
    </div>
</div>

<?php

function generatePDF($data) {
    require_once('tcpdf/tcpdf.php');
    
    // Clear any previous output
    ob_clean();
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator('Job Evaluation System');
    $pdf->SetTitle('Job Evaluation Result');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Create HTML content
    $html = '
    <h1 style="text-align:center;">Classification Evaluation</h1>
    <br>
    <table border="1" cellpadding="5">
        <tr>
            <td width="30%"><strong>Name:</strong></td>
            <td width="70%">' . $data['name'] . '</td>
        </tr>
        <tr>
            <td><strong>Post No:</strong></td>
            <td>' . $data['post_no'] . '</td>
        </tr>
        <tr>
            <td><strong>Post Title:</strong></td>
            <td>' . $data['Post_Title'] . '</td>
        </tr>
        <tr>
            <td><strong>Ministry Department/Division/Unit:</strong></td>
            <td>' . $data['MDA'] . '</td>
        </tr>
        <tr>
            <td><strong>Current Level:</strong></td>
            <td>' . $data['current_level'] . '</td>
        </tr>
        <tr>
            <td><strong>Level Accorded:</strong></td>
            <td>' . $data['level_accorded'] . '</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>' . $data['date'] . '</td>
        </tr>
        <tr>
            <td><strong>Primary Focus:</strong></td>
            <td>Reclassification</td>
        </tr>
    </table>

    <br><br>
    <table border="1" cellpadding="5">
        <tr style="background-color: #f2f2f2;">
            <th width="25%"><strong>Factor</strong></th>
            <th width="35%"><strong>Subfactor</strong></th>
            <th width="20%"><strong>Level</strong></th>
            <th width="20%"><strong>Score</strong></th>
        </tr>
        <tr>
            <td rowspan="3"><strong>Expertise</strong></td>
            <td>Knowledge And Training</td>
            <td>' . $data['Knowledge_and_Training'] . '</td>
            <td rowspan="3">' . $data['total_1'] . '</td>
        </tr>
        <tr>
            <td>Experience</td>
            <td>' . $data['Experience'] . '</td>
        </tr>
        <tr>
            <td>Diversity</td>
            <td>' . $data['Diversity'] . '</td>
        </tr>
        <tr>
            <td rowspan="2"><strong>Critical Thinking</strong></td>
            <td>Complexity</td>
            <td>' . $data['Complexity'] . '</td>
            <td rowspan="2">' . $data['total_2'] . '</td>
        </tr>
        <tr>
            <td>Creativity</td>
            <td>' . $data['Creativity'] . '</td>
        </tr>
        <tr>
            <td rowspan="2"><strong>Communication</strong></td>
            <td>Engagements</td>
            <td>' . $data['Engagement'] . '</td>
            <td rowspan="2">' . $data['total_3'] . '</td>
        </tr>
        <tr>
            <td>Networks</td>
            <td>' . $data['Networks'] . '</td>
        </tr>
        <tr>
            <td rowspan="3"><strong>Service Delivery</strong></td>
            <td>Team Role And Accountability</td>
            <td>' . $data['Teamrole_and_Accountability'] . '</td>
            <td rowspan="3">' . $data['total_4'] . '</td>
        </tr>
        <tr>
            <td>Impact</td>
            <td>' . $data['Impact'] . '</td>
        </tr>
        <tr>
            <td>Consequences Of Error</td>
            <td>' . $data['Consequence_of_Error'] . '</td>
        </tr>
        <tr>
            <td rowspan="2"><strong>Working Conditions</strong></td>
            <td>Physical</td>
            <td>' . $data['Physical'] . '</td>
            <td rowspan="2">' . $data['total_5'] . '</td>
        </tr>
        <tr>
            <td>Mental</td>
            <td>' . $data['Mental_and_Emotional_Demands'] . '</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Total Score:</strong></td>
            <td><strong>' . $data['total'] . '</strong></td>
        </tr>
    </table>

    <br><br>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('evaluation_result.pdf', 'D');
    exit();
}

// Update the data array when calling generatePDF
if(isset($_POST['export_pdf'])) {
    // Get logged in user's name
    $user_id = $_SESSION['user_id'];
    $user_query = mysqli_query($con, "SELECT username FROM users WHERE id = $user_id");
    $user_data = mysqli_fetch_assoc($user_query);
    
    $data = [
        'name' => $user_data['username'], // Now using actual logged-in user's name
        'post_no' => '', // Add the actual post number
        'Post_Title' => $res_11['Post_Title'],
        'MDA' => $res_11['MDA'],
        'current_level' => 'Level 5', // Add the actual current level
        'level_accorded' => 'Level 6', // Add the actual level accorded
        'date' => $res_11['date'],
        'Knowledge_and_Training' => $row_1['Knowledge_and_Training'],
        'Experience' => $row_2['Experience'],
        'Diversity' => $row_3['Diversity'],
        'Complexity' => $row_4['Complexity'],
        'Creativity' => $row_5['Creativity'],
        'Engagement' => $row_6['Engagement'],
        'Networks' => $row_7['Networks'],
        'Teamrole_and_Accountability' => $row_8['Teamrole_and_Accountability'],
        'Impact' => $row_9['Impact'],
        'Consequence_of_Error' => $row_10['Consequence_of_Error'],
        'Physical' => $row_11['Physical'],
        'Mental_and_Emotional_Demands' => $row_12['Mental_and_Emotional_Demands'],
        'total_1' => $total_1,
        'total_2' => $total_2,
        'total_3' => $total_3,
        'total_4' => $total_4,
        'total_5' => $total_5,
        'total' => $total,
        'CM_1' => $row_13['CM_1'],
        'CM_2' => $row_14['CM_2'],
        'CM_3' => $row_15['CM_3'],
        'CM_4' => $row_16['CM_4'],
        'CM_5' => $row_17['CM_5']
    ];
    generatePDF($data);
}

// Then add this button right before the closing </div> of your results container
?>


<!-- Add Font Awesome in the head section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="d-flex gap-2 justify-content-center mt-4">
    <form method="post" action="">
        <input type="hidden" name="export_pdf" value="1">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
    </form>
    
    <a href="export_excel.php?id=<?php echo $id; ?>" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
</div>





   