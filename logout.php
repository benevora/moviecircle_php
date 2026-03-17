<?php

  /* ======================================
   LOGOUT PROCESS
   - Loads system configuration and DAO
   - Calls destroyToken() to remove
     the user's authentication token
   - Redirects user to the homepage
     with a logout success message
  ====================================== */
  require_once("templates/header.php");
  require_once("globals.php");
  require_once("config/db.php");
  require_once("models/User.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");

  $userDao = new UserDAO($conn, $BASE_URL);

  // Destroy session token (logout)
  $userDao->destroyToken();
