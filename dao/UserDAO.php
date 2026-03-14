<?php

  require_once ("models/User.php");
  require_once ("models/Message.php");

  class UserDAO implements UserDAOInterface 
  {

    private $conn;
    private $url;
    private $message;

    /* ======================================
      CONSTRUCTOR
      - Receives database connection
      - Sets base URL
      - Initializes message system
    ====================================== */
    public function __construct(PDO $conn, $url)
    {
      $this->conn = $conn;
      $this->url = $url;
      $this->message = new Message($url);
    }


    public function buildUser($data)
    {
      $user = new User();

      $user->id = $data["id"];
      $user->name = $data["name"];
      $user->lastname = $data["lastname"];
      $user->email = $data["email"];
      $user->password = $data["password"];
      $user->image = $data["image"];
      $user->bio = $data["bio"];
      $user->token = $data["token"];
      $user->is_admin = $data["is_admin"];
      $user->is_banned = $data["is_banned"];

      return $user;
    }


    public function create(User $user, $authUser = false)
    {

      $stmt = $this->conn->prepare("INSERT INTO users(name, lastname, email, password, token)
       VALUES (:name, :lastname, :email, :password, :token) ");
      
      $stmt->bindParam(":name", $user->name);
      $stmt->bindParam(":lastname", $user->lastname);
      $stmt->bindParam(":email", $user->email);
      $stmt->bindParam(":password", $user->password);
      $stmt->bindParam(":token", $user->token);

      $stmt->execute();

      // authenticate user, if auth is true
      if ($authUser) 
      {
        $this->setTokenToSession($user->token);
      }

    }


    public function update(User $user)
    {

    }



    
    /* ======================================
      TOKEN VERIFICATION
      - Checks if a user authentication
      token exists in the session
      - Validates the token against the
      database
      - Returns the authenticated user
      - If the page is protected and the
      token is invalid, redirects the
      user to the homepage
    ====================================== */
    public function verifyToken($protected = false)
    {
      if (!empty($_SESSION["token"])) {

        // get the session token
        $token = $_SESSION["token"];

        $user = $this->findByToken($token);

        if ($user) {
          return $user;
        } else if($protected) {
          // Redirects the unauthenticated user
          $this->message->setMessage(
            "Please authenticate to access this page.",
            "error",
            "index.php");
        }
        
      } else if($protected) {
          // Redirects the unauthenticated user
          $this->message->setMessage(
            "Please authenticate to access this page.",
            "error",
            "index.php");
      }
    }





    public function setTokenToSession($token, $redirect = true)
    {
      // save token in session
      $_SESSION["token"] = $token;

      if ($redirect) {
        
        // Redirects to the user's profile
        $this->message->setMessage(
          "welcome",
          "success",
          "editprofile.php");
      }
    }


    public function authenticateUser($email, $password)
    {

    }


    public function findByEmail($email)
    {

      if ($email != "") {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");

        $stmt->bindParam(":email", $email);

        $stmt->execute();

        if($stmt->rowCount() > 0) {

          $data = $stmt->fetch();
          $user = $this->buildUser($data);

          return $user;

        } else {

          return false;

        }

      } else {

        return false;

      }
    }


    public function findById($id)
    {

    }


    public function findByToken($token)
    {

      if ($token != "") {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");

        $stmt->bindParam(":token", $token);

        $stmt->execute();

        if($stmt->rowCount() > 0) {

          $data = $stmt->fetch();
          $user = $this->buildUser($data);

          return $user;

        } else {

          return false;

        }

      } else {

        return false;

      }
    }


    /* ======================================
      USER LOGOUT
      - Removes the authentication token
      stored in the session
      - Effectively logs the user out
      - Redirects to homepage with
      a confirmation message
    ====================================== */
    public function destroyToken()
    {
      // remove token from session
      unset($_SESSION["token"]);

      // Redirect and display a success message
      $this->message->setMessage(
        "You have successfully logged out!",
        "success",
        "index.php");
    }




    public function changePassword(User $user)
    {

    }


    public function isAdmin(User $user)
    {

    }


    public function setAdmin(User $user, $isAdmin = true)
    {
      
    }
  }
