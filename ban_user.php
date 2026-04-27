<?php

  /* ======================================
    Load global configuration
  ====================================== */
  require_once("globals.php");

  /* ======================================
    Database connection
  ====================================== */
  require_once("config/db.php");

  /* ======================================
    Load DAO and Message system
  ====================================== */
  require_once("dao/UserDAO.php");
  require_once("models/Message.php");

  /* ======================================
    Initialize Message system
  ====================================== */
  $message = new Message($BASE_URL);

  /* ======================================
    Initialize User DAO
  ====================================== */
  $userDao = new UserDAO($conn, $BASE_URL);

  // cVerify authenticated user (protected route)
  $userData = $userDao->verifyToken(true);

  // Check admin permissions
  if(!$userDao->isAdmin($userData)) {
    $message->setMessage("Access denied.", "error", "index.php");
    exit;
  }

  // Get user ID from request
  $id = filter_input(INPUT_GET, "id");

  // Prevent self-ban
  if($id == $userData->id) {
    $message->setMessage("You cannot ban yourself.", "error", "back");
    exit;
  }


  // Execute ban action
  if($id) {
    $userDao->banUser($id);
  } else {
    $message->setMessage("Invalid user.", "error", "back");
  }