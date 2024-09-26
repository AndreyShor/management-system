<?php

    require_once './../controller/ldapConnect.php';
    if (checkValidityGroups($_SESSION['groupStatus']) == true) {
        header("Location: https://www.essex.ac.uk/");
    }


        require_once './../controller/db.php';
        $mysql = new MySql();

        if(isset($_GET['status']) && $_GET['status'] == "changeStatus") {

            $result = $mysql->statusSwitch($_GET['id'], $_GET['name'], $_GET['surname'], $_GET['requestBy']);

            if($result == true) {

                header("Location: http://localhost/view/requests_table.php");
                exit();
            } else{
                header("Location: https://csee.essex.ac.uk");
                echo "Error";
                exit();
            }
        }

        if(isset($_GET['status']) && $_GET['status'] == "delete") {


            $result = $mysql->deletUser($_GET['id']);

            if($result == true) {
                require_once './../controller/email.php';
                $emailService = new emailService();
                $emailService->deleteUserEmail($id, $_GET['name'], $_GET['surname'], $_GET['requestBy']);
                
                header("Location: http://localhost/view/requests_table.php");
                exit();
            } else{
                echo "Error";
                exit();
            }
        } 

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/bundle.css">
    <title>Table</title>
</head>
<body>
<?php include './include/header.php'; ?>


<div class="requests_page">

    <h1 class="table_title">Submitted Requests</h1>

    <h3>Pending Table</h3>
    <table class="pendding_table">
        <tr>
            <th>Name/Surname</th>
            <th>Essex Account</th>
            <th>Role</th>
            <th>Started Date</th>
            <th>Submitted Date</th>
            <th>Last update</th>

            <th>Control</th>
        </tr>

        <?php

                // import Db
                require_once './../controller/db.php';
                $mysql = new MySql();

                //Get tableof content for admin menu
                $DATA = $mysql->getAllPendingUserRequestInfo();
                
                // key paramters to control. Action - current page, Status - anothe page
                // edit.php?action=edit&id=                 Edit
                // ?status=delete&id=                       Delete
                // /view/user_page.php?id=           View
                foreach ($DATA as $d) {
                    $string = str_replace(' ', '', $d['personID']);

                    echo "
                    <tr class='pending'>
                    <td>". $d['userName'] . " " . $d['userSurname'] ."</td>
                    <td>". $d['essexAccount'] ."</td>
                    <td>" .$d['userType'] . "</td>
                    <td>" .$d['startDate'] . "</td>
                    <td>" .$d['submitedDate'] . "</td>
                    <td>" .$d['lastupdate'] . "</td>
                    <th>
                        <a href='edit_local.php?action=edit&id=". $d['personID'] ."'>
                            <button type='submit'class='approve' name='delete'>Edit</button>
                        </a>
                        <a href='?status=delete&id= ". $d['personID'] . "&name=" . $d['userName'] . "&surname=" . $d['userSurname'] . "&requestBy=" . $_SESSION['username'] ."'>
                            <button type='submit'class='approve' name='delete'>Delete</button>
                        </a>
                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                            <button type='submit'class='approve'>Info</button>
                        </a>
                        <a href='?status=changeStatus&id= ". $d['personID'] . "&name=" . $d['userName'] . "&surname=" . $d['userSurname'] .  "&requestBy=" . $_SESSION['username'] ."'>
                            <button type='submit'class='approve' name='approve'>Approve</button>
                        </a>
                    </th>
                </tr>
                    
                    ";
                }

                ?>

    </table>
    
    <h3>Approved Table</h3>
    <table class="pendding_table">
        <tr>
            <th>Name/Surname</th>
            <th>Essex Account</th>
            <th>Role</th>
            <th>Started Date</th>
            <th>Submitted Date</th>
            <th>Last update</th>

            <th>Control</th>
        </tr>

        <?php

                // import Db
                require_once './../controller/db.php';
                $mysql = new MySql();

                //Get tableof content for admin menu
                $DATA = $mysql->getAllApproveUserRequestInfo();
                
                // key paramters to control. Action - current page, Status - anothe page
                // edit.php?action=edit&id=                 Edit
                // ?status=delete&id=                       Delete
                // /view/user_page.php?id=           View
                foreach ($DATA as $d) {
                    $string = str_replace(' ', '', $d['personID']);

                    echo "
                    <tr class='approved'>
                    <td>". $d['userName'] . " " . $d['userSurname'] ."</td>
                    <td>". $d['essexAccount'] ."</td>
                    <td>" .$d['userType'] . "</td>
                    <td>" .$d['startDate'] . "</td>
                    <td>" .$d['submitedDate'] . "</td>
                    <td>" .$d['lastupdate'] . "</td>
                    <th>
                        <a href='edit_local.php?action=edit&id=". $d['personID'] ."'>
                            <button type='submit'class='approve' name='edit'>Edit</button>
                        </a>
                        <a href='?status=delete&id= ". $d['personID'] . "&name=" . $d['userName'] . "&surname=" . $d['userSurname'] .  "&requestBy=" . $_SESSION['username'] . "'>
                            <button type='submit'class='approve' name='delete'>Delete</button>
                        </a>
                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                            <button type='submit'class='approve'>Info</button>
                        </a>
                        <a href='?status=changeStatus&id= ". $d['personID'] .  "&requestBy=" . $_SESSION['username'] ."'>
                            <button type='submit'class='approve' name='approve'>Reject</button>
                        </a>
                    </th>
                </tr>
                    
                    ";
                }

                ?>

    </table>
    </div>
</div>
</body>
</html>