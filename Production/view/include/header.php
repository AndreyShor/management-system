
<?php

// this Header is working as a main block for authentication

require_once './../controller/ldapConnect.php';

session_start();
// block root if person is not logged in 
if ($_SESSION['Login'] != true) { 
  header("Location: https://csee.essex.ac.uk/OSS/");
}

?> 

<header>
        <div class="logo">
            <img src="./../img/UE-white.png" alt="University of Essex">
        </div>

        <h1 class="title_program">Operations Support System</h1>
        <div class="nav">
          <?php

          if ($_SESSION['Login'] == true) {
              echo '<a href="https://csee.essex.ac.uk/OSS/view/main.php" class="link">
                      <div class="text">Request Form</div>
                    </a>';
          }

          if ($_SESSION['Login'] == true) {
            // if ($_SESSION['groupStatus'][0] == "ces-tech-g") {
              echo '<a href="https://csee.essex.ac.uk/OSS/view/user_directory.php" class="link">
                      <div class="text">User Directory</div>
                    </a>';
            // }
          }

          if ($_SESSION['Login'] == true) {
            if (checkValidityGroups($_SESSION['groupStatus']) == true) {
              echo '<a href="https://csee.essex.ac.uk/OSS/view/requests_table.php" class="link">
                      <div class="text">Request Logging</div>
                    </a>';
            }
          }

          if ($_SESSION['Login'] == true) {
              echo '<a href="https://csee.essex.ac.uk/OSS/index.php" class="link">
                      <div class="text">Log Out</div>
                    </a>';
          }

          ?>
        </div>
    </header>