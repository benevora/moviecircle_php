<?php
  /* ======================================
     HEADER TEMPLATE
     - Includes global configuration
     - Loads HTML <head>
     - Displays navigation bar
  ====================================== */

  // Load header template (HTML head + navbar)
  require_once("templates/header.php");

  require_once("dao/UserDAO.php");

  $userDao = new UserDAO($conn, $BASE_URL);

  /* ======================================
   PROTECTED PAGE ACCESS
   - Requires the user to be authenticated
   - If no valid session token is found,
     the user is redirected to index.php
  ====================================== */
  $userData = $userDao->verifyToken(true);

?>



  <!-- ========== MAIN CONTENT ========= -->
  <div id="main-container" class="container-fluid">
    <h1>Profile Editing</h1>
  </div>




<?php
  /* ======================================
     FOOTER TEMPLATE
     - Closes main layout structure
     - Loads footer section
     - Includes JavaScript files
  ====================================== */

  // Load footer template (footer + scrips)
  require_once("templates/footer.php");
?>