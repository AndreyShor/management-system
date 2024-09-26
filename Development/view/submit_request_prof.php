<?php

    require_once './../controller/ldapConnect.php';
    if (checkValidityGroups($_SESSION['groupStatus']) == true) {
        header("Location: https://www.essex.ac.uk/");
    }

    // THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED  ///////////////////

        // import db code
        require_once './../controller/db.php';
        $mysql = new MySql();

        // Send Request on a server 
        if(isset($_POST['ok'])) {

            $result = $mysql->insertUserForm($_POST, "Professional staff");

            if($result["status"] == true) {
                require_once './../controller/email.php';
                $emailService = new emailService();
                $emailService->addUserEmail($result["recordID"], $_POST, "Professional staff");
        
                header("Location: http://localhost/view/done.php");
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
  <title>Submit Request for a Professional Services Staff Members </title>
</head>
<body>
  <?php include './include/header.php'; ?>

  <div class="request_submit_page">
    <h1>Request Form for Professional Services Staff Members </h1>

    <p class="desc">Please, enter all data fields to submite form</p>

    <div class="prof_type_selector">
        <p>Request type</p>
        <select name="prof_selector" id="prof-type-selector">
            <!-- <option value="phd">Phd Student</option> -->
            <option value="fix">Fix-Term Researcher</option>
            <option value="Visitors">Visitors</option>
            <!-- <option value="pro" selected>Professional staff</option>
            <option value="ktp">KTP Memeber</option> -->
        </select>
        <button id="prof-type-button">Select</button>
    </div>


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
                    <?php echo "<input type='text' id='requestBy' name='requestEmail' value='" . $_SESSION['email'] . "' required>" ?>
                </div>

                <h2>Staff Info </h2>

                <div class="input_field">
                    <label for="username">Professional staff Essex Account<strong>*</strong></label>
                    <input type="text" id="account" name="account" required>
                </div>

                <div class="input_field">
                    <label for="username">Professional staff Name<strong>*</strong></label>
                    <input type="text" id="profName" name="name" required>
                </div>

                <div class="input_field">
                    <label for="username">Professional staff Surname<strong>*</strong></label>
                    <input type="text" id="profSurname" name="surname" required>
                </div>
            
                <h2>Facilities Info </h2>

                <div class="input_field">
                    <label for="username">Start Date<strong>*</strong></label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>

                <div class="input_field" >
                    <label for="username">End Date<strong>*</strong></label>
                    <input type="date" id="endDate" name="endDate" value="17/11/2099">
                </div>
                <div class="input_field" style="display: none">
                    <label for="username">Location<strong>*</strong></label>
                    <input type="text" id="location" name="location" >
                </div>
                <div class="input_field">
                    <label for="username">Disability Requirements (PEEP/Adjustments)<strong>*</strong></label>
                    <input type="text" id="disability" name="disability" required>
                </div>

                <button type="submit" name="ok">Submit</button>
            </div>


        </section>
    </form>


  </div>

</body>

<script src="../js/submit_request.js"></script>
</html>