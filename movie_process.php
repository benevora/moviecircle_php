<?php

  require_once("globals.php");
  require_once("config/db.php");
  require_once("models/Movie.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  // check the form type
  $type = filter_input(INPUT_POST, "type");

  // Retrieve user data
  $userData = $userDao->verifyToken();

  if ($type === "create") {

    //receive the input data
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    $movie = new Movie();

    // minimum data validation
    if (!empty($title) && !empty($description) && !empty($category)) {
      
      $movie->title = $title;
      $movie->description = $description;
      $movie->trailer = $trailer;
      $movie->category = $category;
      $movie->length = $length;
      $movie->users_id = $userData->id;


      // Image upload from the movie
      if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
        
        $image = $_FILES["image"];
        
        //types of images allowed
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        // image type check
        if (in_array($image["type"], $imageTypes)) {
        
          // check if jpg
          if (in_array($image["type"], $jpgArray)) {

            $imageFile =  imagecreatefromjpeg($image["tmp_name"]);

            $imageName = $movie->imageGenerateName("jpg");
            $movie->image = $imageName; 
            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

          // Image is png
          } else {

            $imageFile =  imagecreatefrompng($image["tmp_name"]);

            $imageName = $movie->imageGenerateName("png");
            $movie->image = $imageName; 
            imagepng($imageFile, "./img/movies/" . $imageName);
          }

          // generating the image name
          $movie->image = $imageName;

        } else {

           $message->setMessage(
            "Invalid image type, please enter png or jpg.",
            "error",
            "back"
          );
        }
      }


      $movieDao->create($movie);

    } else {

      $message->setMessage(
        "You need to add at least a title, description, and category!",
        "error",
        "back"
      );
    }

  } elseif ($type === "delete") {

    // receive the form data
    $id = filter_input(INPUT_POST, "id");

    $movie = $movieDao->findById($id);

    if ($movie) {
      
      // check if the movie belongs to the user
      if ($movie->users_id === $userData->id) {
        
        $movieDao->destroy($movie->id);
      } else {
          $message->setMessage(
            "Invalid information.",
            "error",
            "index.php"
          );
      }
      
      } else {
        $message->setMessage(
          "Invalid information.",
          "error",
          "index.php"
        );
    }
    

  } elseif ($type === "update") {

    //receive the input data
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    // check if you found the movie
    if ($movieData) {
      
      // check if the movie belongs to the user
      if ($movieData->users_id === $userData->id) {
        
        // minimum data validation
        if (!empty($title) && !empty($description) && !empty($category)) {

          // Film edition
          $movieData->title = $title;
          $movieData->description = $description;
          $movieData->trailer = $trailer;
          $movieData->category = $category;
          $movieData->length = $length;


          // Image upload from the movie
          if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            
            $image = $_FILES["image"];

            //types of images allowed
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            // image type check
            if (in_array($image["type"], $imageTypes)) {
            
              // check if jpg
              if (in_array($image["type"], $jpgArray)) {

                $imageFile =  imagecreatefromjpeg($image["tmp_name"]);

                $imageName = $movieData->imageGenerateName("jpg");
                $movieData->image = $imageName; 
                imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

              // Image is png
              } else {

                $imageFile =  imagecreatefrompng($image["tmp_name"]);

                $imageName = $movieData->imageGenerateName("png");
                $movieData->image = $imageName; 
                imagepng($imageFile, "./img/movies/" . $imageName);
              }

              $movieData->image = $imageName;

            } else {

              $message->setMessage(
                "Invalid image type, please enter png or jpg.",
                "error",
                "back"
              );
            }
          }

          $movieDao->update($movieData);

        } else {
           
          $message->setMessage(
            "You need to add at least a title, description, and category!",
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


    } else {
       $message->setMessage(
          "Invalid information.",
          "error",
          "index.php"
        );
    }
    

  } else {

    $message->setMessage(
      "Invalid information.",
      "error",
      "index.php"
    );
  }