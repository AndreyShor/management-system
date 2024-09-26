<?php

// tHIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED  ///////////////////

    // import db code
    require_once './../controller/db.php';

    $mysql = new MySql();

    // Send Request on a server 
    if(isset($_POST['ok'])) {

        $result = $mysql->insertUserForm($_POST, "Visitor", true);

        if($result["status"] == true) {
            require_once './../controller/email.php';
            $emailService = new emailService();
            $emailService->addUserEmail($result["recordID"], $_POST, "Visitor");
    
            header("Location: http://localhost/view/done.php");
            exit();
        } else{
            echo "Error";
            exit();
        }
    }

// tHIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED - END ///////////////////
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/bundle.css">
  <title>Submit Request for a Visitor</title>
</head>
<body>
  <?php include './include/header.php'; ?>

  <div class="request_submit_page">
    <h1>Request Form for Visitors</h1>

    <p class="desc">Please, enter all data fields to submite form</p>

    <div class="prof_type_selector">
        <p>Request type</p>
        <select name="prof_selector" id="prof-type-selector">
            <!-- <option value="phd" >Phd Student</option> -->
            <option value="fix">Fix-Term Researcher</option>
            <option value="Visitors"selected>Visitors</option>
            <!-- <option value="pro">Professional staff</option>
            <option value="ktp">KTP Memeber</option> -->
        </select>
        <button id="prof-type-button">Select</button>
    </div>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <section class="request_submit_menu">
            <div class="column">

                <h2>Requester Info </h2>

                <div class="input_field">
                    <label for="username">Invited By<strong>*</strong></label>
                    <?php echo "<input type='text' id='invitation' name='supervisor' value='" . $_SESSION['email'] . "' required>" ?>
                </div>

                <div class="input_field">
                    <label for="username">Requested By<strong>*</strong></label>
                    <?php echo "<input type='text' id='requestBy' name='requestBy' value='" . $_SESSION['username'] . "' required>" ?>
                </div>

                <div class="input_field">
                    <label for="username">Requester Email<strong>*</strong></label>
                    <?php echo "<input type='text' id='requestBy' name='requestEmail' value='" . $_SESSION['email'] . "' required>" ?>
                </div>

                <h2>Visitor Info </h2>

                <div class="input_field">
                    <label for="username">Visitor Essex Account<strong>*</strong></label>
                    <input type="text" id="account" name="account" required>
                </div>

                <div class="input_field">
                    <label for="username">Visitor Name<strong>*</strong></label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="input_field">
                    <label for="username">Visitor Surname<strong>*</strong></label>
                    <input type="text" id="surname" name="surname" required>
                </div>

            
                <h2>Facilities Info </h2>

                <div class="input_field">
                    <label for="username">Visitor Start Date<strong>*</strong></label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>

                <div class="input_field">
                    <label for="username">Visitor End Date<strong>*</strong></label>
                    <input type="date" id="endDate" name="endDate" required>
                </div>
                <div class="input_field" style="display:none">
                    <label for="username">Location<strong>*</strong></label>
                    <input type="text" id="location" name="location">
                </div>
                <div class="input_field">
                    <label for="username">Disability Requirements (PEEP/Adjustments)<strong>*</strong></label>
                    <input type="text" id="disability" name="disability" required>
                </div>
                
            </div>


            <div class="column">
                <h2>Visitor Type</h2>

                <!-- <div class="input_field">
                    <label for="checkboxWFH">WFH Approval?<strong>*</strong></label>
                    <input type="checkbox" id="checkboxWFH" name="checkboxWFH" onclick="checkboxClick()">
                </div> -->

                <div class="input_field" id="WFHFieldList" >
                    <label for="equipmentList">Visitor Type<strong>*</strong></label>
                    <input type="text"  id="equipmentList" name="equipmentList" required>
                </div>

                <div class="input_field" id="WFHFieldList" >
                    <label for="equipmentList">Work Pattern</label>
                    <input type="text"  id="INVNumbers" name="INVNumbers">
                </div>
                <button type="submit" name="ok">Submit</button>
            </div>


        </section>
    </form>


  </div>

</body>

<script src="../js/submit_request.js"></script>
</html>