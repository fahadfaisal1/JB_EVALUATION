<?php
include "../conn.php";

if(isset($_POST['register']))
    {
            
           $MDA = $_POST['MDA'];
           $POST_TITLE = $_POST['POST_TITLE'];
           $POST_NUMBER = 0;
           $KNOWLEDGE_TRAINING = $_POST['KNOWLEDGE_TRAINING'];
           $EXPERIENCE = $_POST['EXPERIENCE'];
           $DIVERSITY = $_POST['DIVERSITY'];
           $COMPLEXITY = $_POST['COMPLEXITY'];
           $CREATIVITY = $_POST['CREATIVITY'];
           $ENGAGEMENT = $_POST['ENGAGEMENT'];
           $NETWORK = $_POST['NETWORK'];
           $TEAM_ROLE_ACCOUNTABILITY = $_POST['TEAM_ROLE_ACCOUNTABILITY'];
           $IMPACT = $_POST['IMPACT'];
           $CONSEQUENCES_OF_ERROR = $_POST['CONSEQUENCES_OF_ERROR'];
           $PHYSICAL = $_POST['PHYSICAL'];
           $MENTAL_EMOTIONAL_DEMANDS= $_POST['MENTAL_EMOTIONAL_DEMANDS'];
           $DATE = date('y-m-d');
           $PF= "";
           $COMMENTS_REMARKS= $_POST['COMMENTS_REMARKS'];
           $CM_1= $_POST['CM_1'];
           $CM_2= $_POST['CM_2'];
           $CM_3= $_POST['CM_3'];
           $CM_4= $_POST['CM_4'];
           $CM_5= $_POST['CM_5'];
            

            // Inserting to database
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
             ('$MDA',
             '$POST_TITLE',
             '$POST_NUMBER',
             '$KNOWLEDGE_TRAINING',
             '$EXPERIENCE',
             '$DIVERSITY',
             '$COMPLEXITY',
             '$CREATIVITY',
             '$ENGAGEMENT',
             '$NETWORK',
             '$TEAM_ROLE_ACCOUNTABILITY',
             '$IMPACT',
             '$CONSEQUENCES_OF_ERROR',
             '$PHYSICAL',
             '$MENTAL_EMOTIONAL_DEMANDS',
             '$DATE',
             '$PF',
             '$COMMENTS_REMARKS',
             '$CM_1',
             '$CM_2',
             '$CM_3',
             '$CM_4',
             '$CM_5')";

            $res = mysqli_query($con,$sql);
            $my_id= mysqli_insert_id($con);
            header("location:result.php?id=$my_id");

        }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" contc:\Users\Abc\Desktop\Fahad\JB_EVALUATIONent="Colorlib Templates">

    <!-- Title Page-->
    <title>Evaluation Form</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <!-- <link href="css/main.css" rel="stylesheet" media="all">-->
    <link href="css/cas.css" rel="stylesheet" media="all">     
</head>

<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
                    <h1 class="title">Evaluation Form</h1>
                    
                    <form method="POST">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                   <label class="label">MDA</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="MDA">
                                    <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                    <?php
                                      $sql = "SELECT * from mda";
                                      $res = mysqli_query($con,$sql);
                                      while($row = mysqli_fetch_array($res))
                                      {
                                      ?>  
                                      <option value="<?php 
                                      echo $row['ID']?>"><?php echo $row['MDA']?></option>
                                      <?php 
                                    } 
                                    ?>
                                </select>
                             <div class="select-dropdown"></div>
                            </div>
                                </div>
                            </div>
                            
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">POST TITLE</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="POST_TITLE">
                                            <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                            <?php
                                          $sql = "SELECT * from job_titles";
                                          $res = mysqli_query($con,$sql);
                                          while($row = mysqli_fetch_array($res))
                                          {
                                          ?>  
                                          <option value="<?php 
                                          echo $row['ID']?>"><?php echo $row['Title']?></option>               
                                          <?php 
                            } 
                            ?>
                                        </select>
                                     <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">POST NUMBER</label>
                                    <div class="input-group-icon">
                                        <input class="input--style-4 js-datepicker" type="text" name="birthday" disabled>
                                        <!-- <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="p-t-10">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>EXPERTISE</h2>
                        <div class="row row-space">
                            
                            <div class="col-2">
                                <div class="input-group">
                                   <label class="label">KNOWLEDGE TRAINING</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="KNOWLEDGE_TRAINING">
                                    <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                    <?php
                                 $sql = "SELECT * from factor_score_level";
                                 $res = mysqli_query($con,$sql);
                                 while($row = mysqli_fetch_array($res))
                                 {
                                 ?>  
                                 <option value="<?php 
                                 echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                                 <?php 
                                 } 
                                ?>
        
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                                </div>
                            </div>
                            
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">EXPERIENCE</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="EXPERIENCE">
                                            <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                            <?php
                                       $sql = "SELECT * from factor_score_level LIMIT 13";
                                               $res = mysqli_query($con,$sql);
                                               while($row = mysqli_fetch_array($res))
                                               {
                                               ?>  
                                               <option value="<?php 
                                               echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                                               <?php 
                                             } 
                                             ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">DIVERSITY</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="DIVERSITY">
                                            <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                            <?php
                                       $sql = "SELECT * from factor_score_level";
                                               $res = mysqli_query($con,$sql);
                                               while($row = mysqli_fetch_array($res))
                                               {
                                               ?>  
                                               <option value="<?php 
                                               echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                                               <?php 
                                             } 
                                             ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="p-t-10">
                                        <!-- <label class="radio-container m-r-45">Male
                                            <input type="radio" checked="checked" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Female
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>CRITICAL THINKING</h2>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                   <label class="label">COMPLEXITY</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="COMPLEXITY">
                                    <option disabled="disabled" selected="selected">Choose option</option>
                                    <?php
                               $sql = "SELECT * from factor_score_level";
                                       $res = mysqli_query($con,$sql);
                                       while($row = mysqli_fetch_array($res))
                                       {
                                       ?>  
                                       <option value="<?php 
                                       echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                                       <?php 
                                     } 
                                     ?>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                                </div>
                            </div>
                            
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">CREATIVITY</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="CREATIVITY">
                                            <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                            <?php
                                       $sql = "SELECT * from factor_score_level LIMIT 16";
                                       $res = mysqli_query($con,$sql);
                                       while($row = mysqli_fetch_array($res))
                                       {
                                       ?>  
                                       <option value="<?php 
                                       echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                                       <?php 
                                        } 
                                        ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <!-- <div class="input-group">
                                    <label class="label"></label>
                                    <div class="input-group-icon">
                                        <input class="input--style-4 js-datepicker" type="text" name="birthday">
                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                    </div>
                                </div> -->
                            </div>
                            
                            <div class="col-2">
                                
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="p-t-10">
                                        <!-- <label class="radio-container m-r-45">Male
                                            <input type="radio" checked="checked" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Female
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Communication</h2>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                   <label class="label">Engagement</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="ENGAGEMENT">
                                    <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                    <?php
                               $sql = "SELECT * from factor_score_level LIMIT 13";
                                       $res = mysqli_query($con,$sql);
                                       while($row = mysqli_fetch_array($res))
                                       {
                                       ?>  
                                       <option value="<?php 
                                       echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                                       <?php 
                                     } 
                                     ?>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                                </div>
                            </div>
                            
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Network</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="NETWORK">
                                            <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                            <?php
        $sql = "SELECT * from factor_score_level LIMIT 13";
                $res = mysqli_query($con,$sql);
                while($row = mysqli_fetch_array($res))
                {
                ?>  
                <option value="<?php 
                echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                <?php 
              } 
              ?> 
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="input-group-icon">
                                        <!-- <input class="input--style-4 js-datepicker" type="text" name="birthday">
                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="p-t-10">
                                        <!-- <label class="radio-container m-r-45">Male
                                            <input type="radio" checked="checked" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Female
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Service Delivery</h2>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                   <label class="label">Team Role Accountability</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="TEAM_ROLE_ACCOUNTABILITY">
                                    <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                    <?php
        $sql = "SELECT * from factor_score_level LIMIT 15";
                $res = mysqli_query($con,$sql);
                while($row = mysqli_fetch_array($res))
                {
                ?>  
                <option value="<?php 
                echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                <?php 
              } 
              ?>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                                </div>
                            </div>
                            
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">IMPACT</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="IMPACT">
                                            <option disabled="disabled" selected="selected">Choose option</option>
                                            <?php
        $sql = "SELECT * from factor_score_level LIMIT 15";
                $res = mysqli_query($con,$sql);
                while($row = mysqli_fetch_array($res))
                {
                ?>  
                <option value="<?php 
                echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                <?php 
              } 
              ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Consequences Of Error</label>
                                    <div class="input-group-icon">
                                        
                                        <div class="rs-select2 js-select-simple select--no-search">
                                            <select name="CONSEQUENCES_OF_ERROR">
                                                <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                                <?php
        $sql = "SELECT * from factor_score_level LIMIT 13";
                $res = mysqli_query($con,$sql);
                while($row = mysqli_fetch_array($res))
                {
                ?>  
                <option value="<?php 
                echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                <?php 
              } 
              ?>
                                            </select>
                                            <div class="select-dropdown"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="p-t-10">
                                        <!-- <label class="radio-container m-r-45">Male
                                            <input type="radio" checked="checked" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Female
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Working Enviroment</h2>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                   <label class="label">Physical</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="PHYSICAL">
                                    <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                    <?php
        $sql = "SELECT * from factor_score_level LIMIT 13";
                $res = mysqli_query($con,$sql);
                while($row = mysqli_fetch_array($res))
                {
                ?>  
                <option value="<?php 
                echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                <?php 
              } 
              ?>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                                </div>
                            </div>
                            
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Mental Eomtional Demands</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="MENTAL_EMOTIONAL_DEMANDS">
                                            <!-- <option disabled="disabled" selected="selected">Choose option</option> -->
                                            <?php
                               $sql = "SELECT * from factor_score_level LIMIT 10";
                                       $res = mysqli_query($con,$sql);
                                       while($row = mysqli_fetch_array($res))
                                       {
                                       ?>  
                                       <option value="<?php 
                                       echo $row['ID']?>"><?php echo $row['Finetune']?></option>               
                                       <?php 
                                     } 
                                     ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Comments / Remarks</label>
                                    <div class="input-group-icon">
                                        <textarea id="w3review" name="w3review" rows="4" cols="70">
                                            
                                            </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="p-t-10">
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Commitee Members</h2>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                   <label class="label">Commitee Member 1</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="CM_1">
                                <?php
                                 $sql = "SELECT * from committee_member";
                                         $res = mysqli_query($con,$sql);
                                         while($row = mysqli_fetch_array($res))
                                         {
                                         ?>  
                                         <option value="<?php 
                                         echo $row['ID']?>"><?php echo $row['Full_Name']?></option>               
                                         <?php 
                                       } 
                                       ?>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                                </div>
                            </div>
                            
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Commitee Member 2</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="CM_2">
                                        <?php
                                 $sql = "SELECT * from committee_member";
                                         $res = mysqli_query($con,$sql);
                                         while($row = mysqli_fetch_array($res))
                                         {
                                         ?>  
                                         <option value="<?php 
                                         echo $row['ID']?>"><?php echo $row['Full_Name']?></option>               
                                         <?php 
                                       } 
                                       ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Commitee Member 3</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="CM_3">
        
                                        <?php
                                         $sql = "SELECT * from committee_member";
                                         $res = mysqli_query($con,$sql);
                                         while($row = mysqli_fetch_array($res))
                                         {
                                         ?>  
                                         <option value="<?php 
                                         echo $row['ID']?>"><?php echo $row['Full_Name']?></option>               
                                         <?php 
                                       } 
                                       ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Commitee Member 4</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="CM_4">
        
                                        <?php
                                 $sql = "SELECT * from committee_member";
                                         $res = mysqli_query($con,$sql);
                                         while($row = mysqli_fetch_array($res))
                                         {
                                         ?>  
                                         <option value="<?php 
                                         echo $row['ID']?>"><?php echo $row['Full_Name']?></option>               
                                         <?php 
                                       } 
                                       ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Commitee Member 5</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="CM-5">
                                        <?php
                                 $sql = "SELECT * from committee_member";
                                         $res = mysqli_query($con,$sql);
                                         while($row = mysqli_fetch_array($res))
                                         {
                                         ?>  
                                         <option value="<?php 
                                         echo $row['ID']?>"><?php echo $row['Full_Name']?></option>               
                                         <?php 
                                       } 
                                       ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label"></label>
                                    <div class="p-t-10">
                                        <!-- <label class="radio-container m-r-45">Male
                                            <input type="radio" checked="checked" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Female
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="p-t-15">
                        <input type="submit" class="btn btn-success" name="register"></input>
                            <!-- <button class="btn btn--radius-2 btn--blue" type="submit">
                                SUBMIT</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->