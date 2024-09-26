<?php
// THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED -  POST REQUEST ///////////////////

require_once './../controller/ldapConnect.php';
if (checkValidityGroups($_SESSION['groupStatus']) == true) {
    header("Location: https://www.essex.ac.uk/");
}

// import db code
require_once './../controller/db.php';
$mysql = new MySql();

// check imput fields
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
if(isset($_POST['ok'])) {
    $result = $mysql->updateUserForm($_POST, $_POST['user_id'], true, true);
    require_once './../controller/email.php';

    $emailService = new emailService();
    $emailService->updateUserEmail($_POST['user_id'], $_POST, $_POST['user_type']);

    if($result["status"] == true) {
        header("Location: https://csee.essex.ac.uk/OSS/view/done.php");
        exit();
    } else{
        echo "Error";
        exit();
    }
}


// trim strip and delete html special characters
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
  
  <title>Submit Request</title>
</head>
<body>
  <?php include './include/header.php'; ?>

  <div class="request_submit_page">
    <h1>Edit Form</h1>
    <p class="desc">Please, enter all data fields to submite form</p>


    <?php  

    // tHIS PART OF CODE WILL BE ACTIVATTED WHEN USER ENTERS THE PAGE -  GET REQUEST ///////////////////

            if(isset($_GET['action']) && $_GET['action'] == "edit") {
                require_once './../controller/db.php';
                $mysql = new MySql();

                // get data to populate edit form
                $DATA = $mysql->getUserDataID($_GET['id'])[0];
            }
    // tHIS PART OF CODE WILL BE ACTIVATTED WHEN USER ENTERS THE PAGE -  GET REQUEST END /////////////////// 
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <section class="request_submit_menu">
            <div class="column">
                <h2>Person Info</h2>

                <div class="input_field" style="display:none">
                    <input type="text" id="requestByLocal" name="requestByLocal" value='<?php echo $_SESSION['username']?>' required>
                </div>

                <div class="input_field">
                    <label for="requestBy">Requested By</label>
                    <input type="text" id="requestBy" name="requestBy" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['requestedby']?>' required>
                </div>

                <div class="input_field">
                    <label for="requestEmail">Requester Email</label>
                    <input type="text" id="requestEmail" name="requestEmail" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['requesterEmail']?>' required>
                </div>
                <div class="input_field">
                    <label for="account">Essex Account</label>
                    <input type="text" id="account" name="account" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['essexAccount']?>' required>
                </div>

                <div class="input_field">
                    <label for="account">Person Name</label>
                    <input type="text" id="name" name="name" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['userName']?>' required>
                </div>

                <div class="input_field">
                    <label for="account">Person Surname</label>
                    <input type="text" id="surname" name="surname" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['userSurname']?>' required>
                </div>

                <div class="input_field">
                    <label for="account">Person Superviser/Inviter</label>
                    <input type="text" id="superviser" name="supervisor" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['supervisor']?>'>
                </div>
            
                <h2>Facilities Info </h2>

                <div class="input_field">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="startDate" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['startDate']?>' required>
                </div>

                <div class="input_field">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" name="endDate" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['endDate']?>' required>
                </div>
                <div class="input_field">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['location']?>' required>
                </div>
                <div class="input_field">
                    <label for="disability">Disability Requirements (PEEP/Adjustments)</label>
                    <input type="text" id="disability" name="disability" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['disability']?>' required>
                </div>
            </div>

            <div class="column">
                <h2>Equipment</h2>


                <div class="input_field" id="WFHFieldList">
                    <label for="equipmentList">List of Equipment</label>
                    <input type="text"  id="equipmentList" name="equipmentList" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['equipment']?>' >
                </div>

                <div class="input_field" id="WFHFieldList">
                    <label for="equipmentList">List of INV Numbers</label>
                    <input type="text"  id="INVNumbers" name="INVNumbers" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $DATA['INVNumbers']?>' >
                </div>

                <?php 
                if(isset($_GET['action']) && $_GET['action'] == "edit") echo "
                <div class='input_field'>
                    <label for='startDate'>Room Key</label>
                    <input type='text' id='roomKey' name='roomKey' value='" . $DATA['roomKey'] . "' >
                </div>
                "
                ?>

                <input type="text" name="user_id" value='<?php if(isset($_GET['action']) && $_GET['action'] == "edit") echo $_GET['id']?>' style="display:none">

                <button type="submit" name="ok">

                <?php if(isset($_GET['action']) && $_GET['action'] == "edit") {
                    echo 'Edit';
                    
                    } ?>
                </button>
                <?php if(isset($_GET['action']) && $_GET['action'] == "edit") {
                    echo '<a href="/OSS/view/user_directory.php">
                            <button type="button">Back</button>
                        </a>';
                    
                    } ?>
            </div>

        </section>
    </form>




  </div>

<script src="../js/submit_request.js"></script>
</body>
</html>