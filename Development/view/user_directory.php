<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Directory</title>
    <link rel="stylesheet" type="text/css" href="../css/bundle.css">
</head>
<body>
    <?php
     // import header
     include './include/header.php'; 
     
     ?>

    <div class="user_directory_page">
        <?php
            echo "<h1>Submited records by ". $_SESSION['username'] . "</h1>"
        ?>
        <div class="user_list">

        <?php
            require_once './../controller/db.php';
            $mysql = new MySql();

            $DATA = $mysql->getUserDirectoryByUserType($_SESSION['username']);

            foreach ($DATA as $d) {
                if($d['reqStatus'] == 'pending') {
                    echo "
                    <div class='user_element black'>
                        <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                        <div class='info'>
                            <p><strong>Essex Account: </strong></p>
                            <p class='start_date'>". $d['essexAccount'] ."</p>
                            <p><strong>User Location: </strong></p>
                            <p class='start_date'>". $d['location'] ."</p>
                            <p><strong>Start Date: </strong></p>
                            <p class='start_date'>". $d['startDate'] ."</p>
                            <p><strong>End Date: </strong> </p>
                            <p class='end_date'>". $d['endDate'] ."</p>
                            <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                <button class='more_info'>More Info</button>
                            </a>
                            <a href='edit_local.php?action=edit&id=". $d['personID'] ."'>
                                <button class='more_info'>Update</button>
                            </a>
                        </div>
                    </div>";
                } else {
                    echo "
                    <div class='user_element black'>
                        <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                        <div class='info'>
                            <p><strong>Essex Account: </strong></p>
                            <p class='start_date'>". $d['essexAccount'] ."</p>
                            <p><strong>User Location: </strong></p>
                            <p class='start_date'>". $d['location'] ."</p>
                            <p><strong>Start Date: </strong></p>
                            <p class='start_date'>". $d['startDate'] ."</p>
                            <p><strong>End Date: </strong> </p>
                            <p class='end_date'>". $d['endDate'] ."</p>
                            <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                <button class='more_info'>More Info</button>
                            </a>
                        </div>
                    </div>";
                }
            }
        ?>
        </div>


        <?php
        require_once './../controller/ldapConnect.php';
        if (checkValidityGroups($_SESSION['groupStatus']) == true) { 
            echo"
            <h1>Record Directory</h1>
            <div class='selector_box'>
                <div class='prof_type_selector'>
                    <p>Filter by Role</p>
                    <select name='prof_selector' onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>
                        <option value='http://localhost/view/user_directory.php?filter=all'>Stuff Role</option>
                        <!-- <option value='http://localhost/view/user_directory.php?filter=all'>All Users</option>
                        <option value='http://localhost/view/user_directory.php?filter=phd'>Phd Student</option> -->
                        <option value='http://localhost/view/user_directory.php?filter=fix' >Fix-Term Researcher</option>
                        <option value='http://localhost/view/user_directory.php?filter=visitor'>Visitors</option>
                        <!-- <option value='http://localhost/view/user_directory.php?filter=pro'>Professional staff</option>
                        <option value='http://localhost/view/user_directory.php?filter=ktp'>KTP Memeber</option> -->
                    </select>
                </div>

                <div class='prof_type_selector'>
                <p>Filter by Start Date</p>
                    <select name='prof_selector' onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>
                        <option value='http://localhost/view/user_directory.php?filter=all'>Select Time Range</option>
                        <option value='http://localhost/view/user_directory.php?filter=startDate_1month' >Start in 1 month</option>
                        <option value='http://localhost/view/user_directory.php?filter=startDate_3month'>Start in 3 month</option>
                        <option value='http://localhost/view/user_directory.php?filter=startDate_6month'>Start in 6 month</option>
                    </select>
                </div>

                <div class='prof_type_selector'>
                <p>Filter by End Date</p>
                    <select name='prof_selector' onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>
                        <option value='http://localhost/view/user_directory.php?filter=all'>Select Time Range</option>
                        <option value='http://localhost/view/user_directory.php?filter=endDate_1month' >Finish in 1 month</option>
                        <option value='http://localhost/view/user_directory.php?filter=endDate_3month'>Finish in 3 month</option>
                        <option value='http://localhost/view/user_directory.php?filter=endtDate_6month'>Finish in 6 month</option>
                    </select>
                </div>
        </div>




            <div class='user_list'>
            ";
        }
        ?>
        <?php


        // tHIS PART OF CODE WILL BE ACTIVATTED WHEN USER ENTERS THE PAGE -  GET REQUEST ///////////////////
        if (checkValidityGroups($_SESSION['groupStatus']) == true) { 

                // import db code
                require_once './../controller/db.php';
                $mysql = new MySql();

                // GET LIST of all users 
                if(isset($_GET["filter"]) && $_GET["filter"] == "visitor") {
                    $DATA = $mysql->getUserDirectoryVisitor();
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "phd") {
                    $DATA = $mysql->getUserDirectoryPhd();
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "fix") {
                    $DATA = $mysql->getUserDirectoryFix();
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "pro") {
                    $DATA = $mysql->getUserDirectoryPro();
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "ktp") {
                    $DATA = $mysql->getUserDirectoryKTP();
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "all") {
                    $DATA = $mysql->getUserDirectory();
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "startDate_1month") {
                    $DATA = $mysql->getUserFilterByStartDate(0, 31);
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "startDate_3month") {
                    $DATA = $mysql->getUserFilterByStartDate(31, 93);
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "startDate_6month") {
                    $DATA = $mysql->getUserFilterByStartDate(93, 365);
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "endDate_1month") {
                    $DATA = $mysql->getUserFilterByEndDate(0, 31);
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "endDate_3month") {
                    $DATA = $mysql->getUserFilterByEndDate(31, 93);
                } else if (isset($_GET["filter"]) && $_GET["filter"] == "endtDate_6month") {
                    $DATA = $mysql->getUserFilterByEndDate(93, 186);
                }
                else {
                    $DATA = $mysql->getUserDirectory();
                }


                // itterate through each element and extract data
                foreach ($DATA as $d) {
                    
                    // id in right format
                    str_replace(' ', '', $d['personID']);

                    switch ($d['userType']) {
                        case "Visitor":
                            if($d['reqStatus'] == 'pending') {
                                echo "
                                <div class='user_element green'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                        <a href='edit_local.php?type=visitor&action=edit&id=". $d['personID'] ."'>
                                            <button class='more_info'>Update</button>
                                        </a>
                                    </div>
                                </div>";
                            } else {
                                echo "
                                <div class='user_element green'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                    </div>
                                </div>";
                            }
                            break;
                        case "Fix-term Researcher":
                            if($d['reqStatus'] == 'pending') {
                                echo "
                                <div class='user_element red'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - Fix-term Researcher</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                        <a href='edit_local.php?action=edit&id=". $d['personID'] ."'>
                                            <button class='more_info'>Update</button>
                                        </a>
                                    </div>
                                </div>";
                            } else {
                                echo "
                                <div class='user_element red'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                    </div>
                                </div>";
                            }
                            break;
        
                        case "Phd Student":
                            if($d['reqStatus'] == 'pending') {
                                echo "
                                <div class='user_element blue'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                        <a href='edit_local.php?action=edit&id=". $d['personID'] ."'>
                                            <button class='more_info'>Update</button>
                                        </a>
                                    </div>
                                </div>";
                            } else {
                                echo "
                                <div class='user_element blue'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                    </div>
                                </div>";
                            }
                            break;
                        case "Professional staff":
                            if($d['reqStatus'] == 'pending') {
                                echo "
                                <div class='user_element yellow'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                        <a href='edit_local.php?action=edit&id=". $d['personID'] ."'>
                                            <button class='more_info'>Update</button>
                                        </a>
                                    </div>
                                </div>";
                            } else {
                                echo "
                                <div class='user_element yellow'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                    </div>
                                </div>";
                            }
                            break;
                        case "KTP Memeber":
                            if($d['reqStatus'] == 'pending') {
                                echo "
                                <div class='user_element black'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                        <a href='edit_local.php?action=edit&id=". $d['personID'] ."'>
                                            <button class='more_info'>Update</button>
                                        </a>
                                    </div>
                                </div>";
                            } else {
                                echo "
                                <div class='user_element black'>
                                    <h2 class='title'>". $d['userName'] . " " . $d['userSurname'] . " - " . $d['userType'] . "</h2>
                                    <div class='info'>
                                        <p><strong>Essex Account: </strong></p>
                                        <p class='start_date'>". $d['essexAccount'] ."</p>
                                        <p><strong>User Location: </strong></p>
                                        <p class='start_date'>". $d['location'] ."</p>
                                        <p><strong>Start Date: </strong></p>
                                        <p class='start_date'>". $d['startDate'] ."</p>
                                        <p><strong>End Date: </strong> </p>
                                        <p class='end_date'>". $d['endDate'] ."</p>
                                        <a href='/view/user_page.php?id=". $d['personID'] ."'>
                                            <button class='more_info'>More Info</button>
                                        </a>
                                    </div>
                                </div>";
                            }
                            break;

                    }


                }

        }
        // tHIS PART OF CODE WILL BE ACTIVATTED WHEN USER ENTERS THE PAGE -  GET REQUEST END ///////////////////
        ?>
        
        </div>

    </div>
</body>
</html>