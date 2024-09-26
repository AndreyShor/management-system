<?php 

    // it wouldn't allow to acces web site if pperson is not a staff mamber
    require_once './../controller/ldapConnect.php';
    $corestaffGroup = "cesstaff-core-g";
    if (checkValidityGroup($_SESSION['groupStatus'], $corestaffGroup) == true) {
        header("Location: https://www.essex.ac.uk/");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>

    <link rel="stylesheet" type="text/css" href="../css/bundle.css">
</head>
<body>
    <?php include './include/header.php'; ?>

    <div class="request_submit_page">
    <h1>Choice Staff Role to Submitte</h1>

    <p class="desc">Please, Choice type of user to add into directory of active users</p>

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
    </div>


    <script src="../js/submit_request.js"></script>
</body>
</html>