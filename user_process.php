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

  // Updating the user
  if($type === "update") {

    // Retrieve user data
    $userData = $userDao->verifyToken();

    // Receive post data
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    // Create a new user object
    $user = new User();

    // fill in the user data
    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    $userDao->update($userData);

  } elseif($type === "changepassword") {

    // Receive post data
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // Retrieve user data
    $userData = $userDao->verifyToken();
    $id = $userData->id;


    if ($password == $confirmpassword) {

      // Create a new user object
      $user = new User();

      $finalPassword = $user->generatePassword($password);

      $user->password = $finalPassword;
      $user->id = $id;

      $userDao->changePassword($user);
      
    } else {
      $message->setMessage(
          "The passwords are not the same.",
          "error",
          "back"
        );
    }


  } elseif ($type === "updatebio") {

    // Retrieve user data
    $userData = $userDao->verifyToken();

    $bio = filter_input(INPUT_POST, "bio");

    // Create a new user object
    $user = new User();

    $userData->bio = $bio;
    
    // Image upload
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
     
      $image = $_FILES["image"];

      //types of images allowed
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
      $jpgArray = ["image/jpeg", "image/jpg"];

      // image type check
      if(in_array($image["type"], $imageTypes)) {

        // check if jpg
        if (in_array($image["type"], $jpgArray)) {

          $imageFile =  imagecreatefromjpeg($image["tmp_name"]);

          $imageName = $user->imageGenerateName("jpg");
          imagejpeg($imageFile, "./img/users/" . $imageName, 100);

        // Image is png
        } else {

          $imageFile =  imagecreatefrompng($image["tmp_name"]);

          $imageName = $user->imageGenerateName("png");
          imagepng($imageFile, "./img/users/" . $imageName);
        }


        $userData->image = $imageName;

      } else {
        $message->setMessage(
          "Invalid image type, please enter png or jpg.",
          "error",
          "back"
        );
      }

    }

    $userDao->update($userData);

  } else {
    $message->setMessage(
      "Invalid information.",
      "error",
      "index.php"
    );
  }