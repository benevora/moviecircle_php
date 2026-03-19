<?php
  /* ======================================
     HEADER TEMPLATE
     - Includes global configuration
     - Loads HTML <head>
     - Displays navigation bar
  ====================================== */

  // Load header template (HTML head + navbar)
  require_once("templates/header.php");

  require_once("models/User.php");
  require_once("dao/UserDAO.php");

  $user = new User();
  $userDao = new UserDAO($conn, $BASE_URL);

  /* ======================================
   PROTECTED PAGE ACCESS
   - Requires the user to be authenticated
   - If no valid session token is found,
     the user is redirected to index.php
  ====================================== */

  // Verify user token (true = protected page)
  $userData = $userDao->verifyToken(true);

  // Get user's full name using model method
  $fullName = $user->getFullName($userData);

  // Set default image if user has no profile picture
  if ($userData->image == "") {
    $userData->image = "user.png";
  }

?>



  <!-- ========== MAIN CONTENT ========= -->
  <div id="main-container" class="container-fluid edit-profile-page">
    <div class="col-md-12">

      <!-- ===============================
         USER PROFILE UPDATE FORM
         - Sends data to user_process.php
      ================================ -->
      <form action="<?= $BASE_URL ?>user_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="update">
        <div class="row">
          <div class="col-md-4">
            
            <!-- Display user full name -->
            <h1><?= $fullName ?></h1>
            <p class="page-description">Change your information in the form below:</p>

            <!-- USER NAME -->
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="<?= $userData->name ?>">
            </div>

            <!-- USER LAST NAME -->
            <div class="form-group">
              <label for="lastname">Last name:</label>
              <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter your last name" value="<?= $userData->lastname ?>">
            </div>

            <!-- USER EMAIL (READ-ONLY) -->
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="text" readonly class="form-control disabled" id="email" name="email" placeholder="Enter your email" value="<?= $userData->email ?>">
            </div>
            
            <!-- SUBMIT BUTTON -->
            <input type="submit" class="btn card-btn" value="Change">

          </div>




          <!-- ===============================
             PROFILE IMAGE + BIO SECTION
          ================================ -->
          <div class="col-md-4">

            <!-- Display current profile image -->
            <div id="profile-image-container" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')">
            </div>

            <!-- IMAGE UPLOAD -->
            <div class="form-group">
              <label for="image">Photo:</label>
              <input type="file" class="form-control-file" name="image">
            </div>

            <!-- USER BIO -->
            <div class="form-group">
              <label for="bio">About you:</label>
              <textarea class="form-control" name="bio" id="bio" rows="5" placeholder="Tell us who you are, what you do, and where you work..."><?= $userData->bio ?></textarea>
            </div>

          </div>
        </div>
      </form>

      <!-- ===============================
        CHANGE PASSWORD SECTION
      ================================ -->
      <div class="row" id="change-password-container">
        <div class="col-md-4">

          <h2>Change password</h2>
          <p class="page-description">Enter your new password and confirm to change your password:</p>

          <!-- PASSWORD CHANGE FORM -->
          <form action="<?= $BASE_URL ?>user_process.php" method="POST">

            <input type="hidden" name="type" value="changepassword">
            
            <!-- NEW PASSWORD -->
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password">
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">
              <label for="confirmpassword">Password confirmation:</label>
              <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm your new password">
            </div>

            <input type="submit" class="btn card-btn" value="Change password">
          </form>

        </div>
      </div>
    </div>
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