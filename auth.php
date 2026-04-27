<?php
  /* ==================================================
     AUTHENTICATION PAGE
     --------------------------------------------------
     Purpose:
     - Provides Login and Registration forms
     - Uses shared layout templates (header & footer)
     - Sends form data via POST for processing
  ================================================== */

  /* ======================================
    Load header template
  ====================================== */
  require_once("templates/header.php");
?>

<!-- ======================================
  MAIN AUTH CONTAINER
====================================== -->
<div id="main-container" class="container-fluid" >
  <div class="col-md-12">
    <div class="row" id="auth-row">


       <!-- ======================================
        LOGIN SECTION
        - Authenticates existing users
      ====================================== -->
      <div class="col-md-4" id="login-container">
        <h2>Login</h2>

        <!-- Login Form -->
        <form action="<?= $BASE_URL ?>auth_process.php" method="POST">

          <!-- Hidden field used to identify form type -->
          <input type="hidden" name="type" value="login">

          <!-- Email -->
          <div class="form-group">
            <label for="email">Email:</label>
            <input  type="email" 
                    class="form-control" 
                    id="email" name="email" 
                    placeholder="Enter your email"
                    required>
          </div>
          
          <!-- Password -->
          <div class="form-group">
            <label for="password">Password:</label>
            <input  type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Enter your password"
                    required>
          </div>

          <!-- Submit Button -->
          <input type="submit" class="btn card-btn" value="Login">
        </form>
      </div>

       <!-- ======================================
        REGISTER SECTION
        - Creates new user accounts
      ====================================== -->
      <div class="col-md-4" id="register-container">
        <h2>Create Account</h2>

        <!-- Registration Form -->
        <form action="<?= $BASE_URL ?>auth_process.php" method="POST">

          <!-- Hidden field used to identify form type -->
          <input type="hidden" name="type" value="register">

          <!-- Email -->
          <div class="form-group">
            <label for="email">Email:</label>
            <input  type="email" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email">
          </div>

          <!-- First Name -->
          <div class="form-group">
            <label for="name">Name:</label>
            <input  type="text" 
                    class="form-control" 
                    id="name" 
                    name="name" 
                    placeholder="Enter your name">
          </div>

          <!-- Last Name -->
          <div class="form-group">
            <label for="lastname">Last Name:</label>
            <input  type="text" 
                    class="form-control" 
                    id="lastname" 
                    name="lastname" 
                    placeholder="Enter your last name">
          </div>

          <!-- Password -->
          <div class="form-group">
            <label for="password">Password:</label>
            <input  type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Enter your password">
          </div>

          <!-- PConfirm Password  -->
          <div class="form-group">
            <label for="confirmpassword">Password confirmation:</label>
            <input  type="password" 
                    class="form-control" 
                    id="confirmpassword" 
                    name="confirmpassword" 
                    placeholder="Confirm your password">
          </div>

          <!-- Submit -->
          <input type="submit" class="btn card-btn" value="Register">

        </form>
      </div>

    </div>
  </div>
</div>

<?php

  /* ======================================
    Load footer template
  ====================================== */
  // Load footer template (footer + scrips)
  require_once("templates/footer.php");
?>