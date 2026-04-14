<?php

  require_once("globals.php");
  require_once("config/db.php");
  require_once("models/User.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");

  $message = new Message($BASE_URL);
  
  $userDao = new UserDAO($conn, $BASE_URL);
  

  // check the form type
  $type = filter_input(INPUT_POST, "type");

  // form type Verification
  if ($type === "register") {

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // minimum data verification
    if ($name && $lastname && $email && $password) {

      // check if the passwords match
      if ($password === $confirmpassword) {

        // check if the email is already registered in the system
        if($userDao->findByEmail($email) === false ){

          $user = new User();

          // Token and password creation
          $userToken = $user->generateToken();
          $finalPassword = $user->generatePassword($password);

          $user->name = $name;
          $user->lastname = $lastname;
          $user->email = $email;
          $user->password = $finalPassword;
          $user->token = $userToken;

          $auth = true;

          $userDao->create($user, $auth);

        } else {
          
          // Send an error message, user already exists
          $message->setMessage(
            "User already registered, please try another email.",
            "error",
            "back"
          );

        }
        
      } else {

        // Send an error message saying the passwords don't match
        $message->setMessage(
          "The passwords are not the same.",
          "error",
          "back"
        );

      }
      
    } else {
      
      // Send a missing data error message
      $message->setMessage(
        "Please fill in all fields.",
        "error",
        "back"
      );

    }


   
  } else if ($type === "login") {

    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    // Attempts to authenticate user
    if ($userDao->authenticateUser($email, $password)) {

       $message->setMessage(
          "welcome",
          "success",
          "profile.php"
      );
      
    // Redirects the user if authentication fails
    } else {
      
      $message->setMessage(
        "Incorrect username or password.",
        "error",
        "back"
      );

    }
    
  } else {

    $message->setMessage(
      "Invalid information.",
      "error",
      "index.php"
    );

  }