<?php

  require_once("globals.php");
  require_once("config/db.php");
  require_once("dao/UserDAO.php");
  require_once("models/Message.php");

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);

  // check logged user
  $userData = $userDao->verifyToken(true);

  // check admin
  if(!$userDao->isAdmin($userData)) {
    $message->setMessage("Access denied.", "error", "index.php");
    exit;
  }

  // get user id
  $id = filter_input(INPUT_GET, "id");

  if($id == $userData->id) {
    $message->setMessage("You cannot ban yourself.", "error", "back");
    exit;
  }


  if($id) {
    $userDao->banUser($id);
  } else {
    $message->setMessage("Invalid user.", "error", "back");
  }