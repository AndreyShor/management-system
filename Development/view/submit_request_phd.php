<?php
// THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED  ///////////////////

    require_once './../controller/ldapConnect.php';
    if (checkValidityGroups($_SESSION['groupStatus']) == true) {
        header("Location: https://www.essex.ac.uk/");
    }
// import db code
require_once './../controller/db.php';
$mysql = new MySql();


// check data fields validity
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_POST["requestBy"] = test_input($_POST["requestBy"]);
    $_POST["requestEmail"] = test_input($_POST["requestEmail"]);
    $_POST["account"] = test_input($_POST["account"]);
    $_POST["supervisor"] = test_input($_POST["supervisor"]);
    $_POST["startDate"] = test_input($_POST["startDate"]);
    $_POST["endDate"] = test_input($_POST["endDate"]);
    $_POST["location"] = test_input($_POST["location"]); 
    $_POST["disability"] = test_input($_POST["disability"]);
    $_POST["equipmentList"] = test_input($_POST["equipmentList"]);
  }

  // Send Request on a server 
if (isset($_POST['ok'])) {
    $result = $mysql->insertUserForm($_POST, "Phd Student", true);

    if($result["status"] == true) {
        require_once './../controller/email.php';
        $emailService = new emailService();
        $emailService->addUserEmail($result["recordID"], $_POST, "Phd Student");

        header("Location: http://localhost/view/done.php");
        exit();
    } else{
        echo "Error";
        exit();
    }
}

// test input function
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

// THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED - END ///////////////////

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/bundle.css">
  
  <title>Submit Request for Phd</title>
</head>
<body>
  <?php include './include/header.php'; ?>

  <div class="request_submit_page">
    <h1>Request Form for PHD Student</h1>
    <p class="desc">Please, enter all data fields to submite form</p>


    <div class="prof_type_selector">
        <p>Request type</p>
        <select name="prof_selector" id="prof-type-selector">
            <!-- <option value="phd" selected>Phd Student</option> -->
            <option value="fix">Fix-Term Researcher</option>
            <option value="Visitors">Visitors</option>
            <!-- <option value="pro">Professional staff</option>
            <option value="ktp">KTP Memeber</option> -->
        </select>
        <button id="prof-type-button">Select</button>
    </div>';

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
                    <?php echo "<input type='text' id='requestBy' name='requestEmail' value='" . $_SESSION['email'] . "' required>" ?>
                </div>

                <div class="input_field">
                    <label for="account">Supervisor Email<strong>*</strong></label>
                    <?php echo "<input type='text' id='supervisor' name='supervisor' value='" . $_SESSION['email'] . "' required>" ?>
                </div>

                <h2>Phd Info </h2>

                <div class="input_field">
                    <label for="username">Essex Account of Phd Student (username)<strong>*</strong></label>
                    <input type="text" id="account" name="account" required>
                </div>

                <div class="input_field">
                    <label for="username">Phd Name<strong>*</strong></label>
                    <input type="text" id="profName" name="name" required>
                </div>

                <div class="input_field">
                    <label for="username">Phd Surname<strong>*</strong></label>
                    <input type="text" id="profSurname" name="surname" required>
                </div>

            
                <h2>Facilities Info </h2>

                <div class="input_field">
                    <label for="username">Start Date<strong>*</strong></label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>

                <div class="input_field">
                    <label for="username">Expected Completion<strong>*</strong></label>
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
                    <label for="equipmentList">Research Field<strong>*</strong></label>
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

<script src="../js/submit_request.js"></script>
</body>
</html>