<?php

// THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED -  POST REQUEST ///////////////////

    // import db code
    require_once './../controller/db.php';
    $mysql = new MySql();


    // Send Request on a server 
    if(isset($_POST['ok'])) {

        print_r($_POST);

        $result = $mysql->insertUserForm($_POST, "Fix-term Researcher");


        if($result["status"] == true) {
            require_once './../controller/email.php';
            $emailService = new emailService();
            $emailService->addUserEmail($result["recordID"], $_POST, "Fix-term Researcher");

            header("Location: https://csee.essex.ac.uk/OSS/view/done.php");
            exit();
        } else{
            echo "Error";
            exit();
        }
        
    }

// THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED - END ///////////////////
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/bundle.css">
  <title>Submit Request for a for Fix-Term Academi </title>
</head>
<body>
  <?php include './include/header.php'; ?>

  <div class="request_submit_page">
    <h1>Request Form for Fix-Term Researcher</h1>

    <p class="desc">Please, enter all data fields to submite form</p>

    <div class="prof_type_selector">
        <p>Request type</p>
        <select name="prof_selector" id="prof-type-selector">
            <!-- <option value="phd">Phd Student</option> -->
            <option value="fix" selected>Fix-Term Researcher</option>
            <option value="Visitors">Visitors</option>
            <!-- <option value="pro">Professional staff</option>
            <option value="ktp">KTP Memeber</option> -->
        </select>
        <button id="prof-type-button">Select</button>
    </div>

    <!-- Prevent special char -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <section class="request_submit_menu">
            <div class="column">

                <h2>Requester Info </h2>

                
                <div class="input_field">
                    <label for="username">Requested By<strong>*</strong></label>
                    <?php echo "<input type='text' id='requestBy' name='requestBy' value='" . $_SESSION['username'] . "' required>" ?>
                </div>

                <div class="input_field">
                    <label for="username">Requester Email<strong>*</strong></label>
                    <?php echo "<input type='text' id='requestEmail' name='requestEmail' value='" . $_SESSION['email'] . "' required>" ?>
                </div>

                <h2>Academic Info </h2>

                <div class="input_field">
                    <label for="username">Researcher Essex Account (username)<strong>*</strong></label>
                    <input type="text" id="account" name="account" required>
                </div>

                <div class="input_field">
                    <label for="username">Researcher Name<strong>*</strong></label>
                    <input type="text" id="profName" name="name" required>
                </div>

                <div class="input_field">
                    <label for="username">Researcher Surname<strong>*</strong></label>
                    <input type="text" id="profSurname" name="surname" required>
                </div>
            
                <h2>Facilities Info </h2>

                <div class="input_field">
                    <label for="username">Start Date<strong>*</strong></label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>

                <div class="input_field">
                    <label for="username">End Date<strong>*</strong></label>
                    <input type="date" id="endDate" name="endDate" required>
                </div>
                <div class="input_field" style="display:none">
                    <label for="username">Location<strong>*</strong></label>
                    <input type="text" id="location" name="location" >
                </div>
                <div class="input_field">
                    <label for="username">Disability Requirements (PEEP/Adjustments)<strong>*</strong></label>
                    <input type="text" id="disability" name="disability" required>
                </div>
            </div>

            <div class="column">
                <h2>Research</h2>

                <!-- <div class="input_field">
                    <label for="checkboxWFH">WFH Approval?<strong>*</strong></label>
                    <input type="checkbox" id="checkboxWFH" name="checkboxWFH" onclick="checkboxClick()">
                </div> -->

                <div class="input_field" id="WFHFieldList" >
                    <label for="equipmentList">Research Area<strong>*</strong></label>
                    <input type="text"  id="equipmentList" name="equipmentList" required>
                </div>

                <div class="input_field" id="WFHFieldList" >
                    <label for="equipmentList">Research Topic</label>
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