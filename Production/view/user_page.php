<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/bundle.css">
    <title>User Page - Andrejs Sorstkins</title>
</head>
<body>
    <?php include './include/header.php'; ?>

    <div class="user_page">

    <?php

    // tHIS PART OF CODE WILL BE ACTIVATTED WHEN USER ENTERS THE PAGE -  GET REQUEST ///////////////////

             // import db code
            require_once './../controller/db.php';
            $mysql = new MySql();

            // Get specific user record 
            $DATA = $mysql->getUserDataID($_GET['id'])[0];

            // format standartizing 
            str_replace(' ', '', $DATA['personID']);


            switch ($DATA['userType']) {
                case "Visitor":
                    echo "
                    <div class='user_data'>
                    <h1 class='fullname green'>". $DATA['userName'] . " " . $DATA['userSurname'] . "</h1>
                    
                    <div class='user_info'>
                        <h2>Requester Information</h2>

                        <div class='element requester'>Requester: ". $DATA['requestedby'] . "</div>
                        <div class='element req_email'>Requester Email: ". $DATA['requesterEmail'] . "</div>
                    
                    </div>

                    <div class='user_info'>
                        <h2>User Information</h2>
        
                        <div class='element essex_account'>". $DATA['userType'] . " Essex Account: ". $DATA['essexAccount'] . "</div>
                        <div class='element name'>". $DATA['userType'] . " Name: ". $DATA['userName'] . "</div>
                        <div class='element surname'>". $DATA['userType'] . " Surname: ". $DATA['userSurname'] ."</div>
                        <div class='element surname'>". $DATA['userType'] . "  Invited By: ". $DATA['supervisor'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Facilities Information</h2>
        
                        <div class='element start_date'>Start Date: ". $DATA['startDate'] ."</div>
                        <div class='element end_date'>End Date: ". $DATA['endDate'] ."</div>
                        <div class='element location'>Location: ". $DATA['location'] ."</div>
                        <div class='element disability'>Disability: ". $DATA['disability'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Visitor Type</h2>
                        <div class='element start_date'>Visitor Type: ". $DATA['equipment'] ." </div>
                        <div class='element start_date'>Work Pattern: ". $DATA['INVNumbers'] ." </div>
                    </div>
        
        
                    <a href=\"javascript:history.go(-1)\">
                        <button>Back</button>
                    </a>
        
                </div>
                    ";
                    break;

                case "Fix-term Researcher":
                    echo "
                    <div class='user_data'>
                    <h1 class='fullname red'>". $DATA['userName'] . " " . $DATA['userSurname'] . "</h1>

                    <div class='user_info'>
                        <h2>Requester Information</h2>

                        <div class='element requester'>Requester: ". $DATA['requestedby'] . "</div>
                        <div class='element req_email'>Requester Email: ". $DATA['requesterEmail'] . "</div>
                    
                    </div>

                    <div class='user_info'>
                        <h2>User Information</h2>

                        <div class='element essex_account' >Researcher Essex Account: ". $DATA['essexAccount'] . "</div>
                        <div class='element name'> Researcher Name: ". $DATA['userName'] . "</div>
                        <div class='element surname'>Researcher Surname: ". $DATA['userSurname'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Facilities Information</h2>
        
                        <div class='element start_date'>Start Date: ". $DATA['startDate'] ."</div>
                        <div class='element end_date'>End Date: ". $DATA['endDate'] ."</div>
                        <div class='element location'>Location: ". $DATA['location'] ."</div>
                        <div class='element disability'>Disability: ". $DATA['disability'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Research</h2>
            
                        <div class='element start_date'>Research Area: ". $DATA['equipment'] ." </div>
                        <div class='element start_date'>Research Topic: ". $DATA['INVNumbers'] ." </div>
                    </div>
        
                    <a href=\"javascript:history.go(-1)\">
                        <button>Back</button>
                    </a>
        
                </div>
                    ";
                    break;

                case "Phd Student":
                    echo "
                    <div class='user_data'>
                    <h1 class='fullname blue'>". $DATA['userName'] . " " . $DATA['userSurname'] . "</h1>
                    <div class='user_info'>
                        <h2>Requester Information</h2>

                        <div class='element requester'>Requester: ". $DATA['requestedby'] . "</div>
                        <div class='element req_email'>Requester Email: ". $DATA['requesterEmail'] . "</div>
                        <div class='element surname'>". $DATA['userType'] . " Supervisor: ". $DATA['supervisor'] ."</div>

                    </div>
                    <div class='user_info'>
                        <h2>User Information</h2>
        
                        <div class='element essex_account'>". $DATA['userType'] . " Essex Account: ". $DATA['essexAccount'] . "</div>
                        <div class='element name'>". $DATA['userType'] . " Name: ". $DATA['userName'] . "</div>
                        <div class='element surname'>". $DATA['userType'] . " Surname: ". $DATA['userSurname'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Facilities Information</h2>
        
                        <div class='element start_date'>Start Date: ". $DATA['startDate'] ."</div>
                        <div class='element end_date'>End Date: ". $DATA['endDate'] ."</div>
                        <div class='element location'>Location: ". $DATA['location'] ."</div>
                        <div class='element disability'>Disability: ". $DATA['disability'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Research</h2>
            
                        <div class='element start_date'>Research Field: ". $DATA['equipment'] ." </div>
                        <div class='element start_date'>Research Topic: ". $DATA['INVNumbers'] ." </div>
                        <div class='element end_date'>Key: ". $DATA['roomKey'] ."</div>
                    </div>
        
                    <a href=\"javascript:history.go(-1)\">
                        <button>Back</button>
                    </a>
        
                </div>
                    ";
                    break;
                case "Professional staff":
                    echo "
                    <div class='user_data'>
                    <h1 class='fullname yellow'>". $DATA['userName'] . " " . $DATA['userSurname'] . "</h1>
                    
                    <div class='user_info'>
                    <h2>Requester Information</h2>

                        <div class='element requester'>Requester: ". $DATA['requestedby'] . "</div>
                        <div class='element req_email'>Requester Email: ". $DATA['requesterEmail'] . "</div>
                
                    </div>

                    <div class='user_info'>
                        <h2>User Information</h2>
        
                        <div class='element essex_account'>". $DATA['userType'] . " Essex Account: ". $DATA['essexAccount'] . "</div>
                        <div class='element name'>". $DATA['userType'] . " Name: ". $DATA['userName'] . "</div>
                        <div class='element surname'>". $DATA['userType'] . " Surname: ". $DATA['userSurname'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Facilities Information</h2>
        
                        <div class='element start_date'>Start Date: ". $DATA['startDate'] ."</div>
                        <div class='element end_date'>End Date: ". $DATA['endDate'] ."</div>
                        <div class='element location'>Location: ". $DATA['location'] ."</div>
                        <div class='element disability'>Disability: ". $DATA['disability'] ."</div>
                    </div>
        
                    <a href=\"javascript:history.go(-1)\">
                        <button>Back</button>
                    </a>
        
                </div>
                    ";
                    break;
                case "KTP Memeber":
                    echo "
                    <div class='user_data'>
                    <h1 class='fullname black'>". $DATA['userName'] . " " . $DATA['userSurname'] . "</h1>

                    <h2>Requester Information</h2>
                    <div class='user_info'>
                        <div class='element requester'>Requester: ". $DATA['requestedby'] . "</div>
                        <div class='element req_email'>Requester Email: ". $DATA['requesterEmail'] . "</div>
                        <div class='element surname'>". $DATA['userType'] . " Company: ". $DATA['supervisor'] ."</div>
            
                    </div>
        
                    <div class='user_info'>
                        <h2>User Information</h2>
        
                        <div class='element requester'>Requester: ". $DATA['requestedby'] . "</div>
                        <div class='element req_email'>Requester Email: ". $DATA['requesterEmail'] . "</div>
                        <div class='element essex_account'>". $DATA['userType'] . " Essex Account: ". $DATA['essexAccount'] . "</div>
                        <div class='element name'>". $DATA['userType'] . " Name: ". $DATA['userName'] . "</div>
                        <div class='element surname'>". $DATA['userType'] . " Surname: ". $DATA['userSurname'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Facilities Information</h2>
        
                        <div class='element start_date'>Start Date: ". $DATA['startDate'] ."</div>
                        <div class='element end_date'>End Date: ". $DATA['endDate'] ."</div>
                        <div class='element location'>Location: ". $DATA['location'] ."</div>
                        <div class='element disability'>Disability: ". $DATA['disability'] ."</div>
                    </div>
        
                    <div class='user_info'>
                        <h2>Supervisor Information</h2>
            
                        <div class='element start_date'>Academic Lead: ". $DATA['equipment'] ." </div>
                        <div class='element start_date'>Co-investigator: ". $DATA['INVNumbers'] ." </div>
                        <div class='element end_date'>Key: ". $DATA['roomKey'] ."</div>
                    </div>
        
                    <a href=\"javascript:history.go(-1)\">
                        <button>Back</button>
                    </a>
        
                </div>
                    ";
                    break;

            }

    // tHIS PART OF CODE WILL BE ACTIVATTED WHEN USER ENTERS THE PAGE -  GET REQUEST END ///////////////////

    ?>
        
        </div>


    </div>
</body>
</html>