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




    /* ======================================
      BUILD USER
      - Receives database data (array)
      - Converts it into a User object
      - Returns a fully populated User
    ====================================== */
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

    /* ======================================
      CREATE USER
      - Inserts a new user into database
      - Stores basic user information
      - Optionally logs the user in
    ====================================== */
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

      // Automatically logs in the user after registration
      if ($authUser) 
      {
        $this->setTokenToSession($user->token);
      }

    }




    /* ======================================
      UPDATE USER
      - Updates user data in database
      - Can update name, email, image, bio, token
      - Optionally redirects with success message
    ====================================== */
    public function update(User $user, $redirect = true)
    {
      $stmt = $this-> conn->prepare("UPDATE users SET
        name = :name,
        lastname = :lastname,
        email = :email,
        image = :image,
        bio = :bio,
        token = :token
        WHERE id = :id
      ");

      $stmt->bindParam(":name", $user->name);
      $stmt->bindParam(":lastname", $user->lastname);
      $stmt->bindParam(":email", $user->email);
      $stmt->bindParam(":image", $user->image);
      $stmt->bindParam(":bio", $user->bio);
      $stmt->bindParam(":token", $user->token);
      $stmt->bindParam(":id", $user->id);

      $stmt->execute();

         if ($redirect) {
        
        // Redirects to the user's profile
        $this->message->setMessage(
          "Data updated successfully",
          "success",
          "editprofile.php"
        );
      }

    }



    
    /* ======================================
      TOKEN VERIFICATION
      - Checks if a session token exists
      - Validates token against database
      - Returns authenticated user if valid
      - Redirects if page is protected
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
            "index.php"
          );
        }
        
      } else if($protected) {
          // Redirects the unauthenticated user
          $this->message->setMessage(
            "Please authenticate to access this page.",
            "error",
            "index.php"
          );
      }
    }




    /* ======================================
      SET TOKEN TO SESSION
      - Stores authentication token in session
      - Used to keep user logged in
      - Optionally redirects after login
    ====================================== */
    public function setTokenToSession($token, $redirect = true)
    {
      // save token in session
      $_SESSION["token"] = $token;

      if ($redirect) {
        
        // Redirects to the user's profile
        $this->message->setMessage(
          "welcome",
          "success",
          "editprofile.php"
        );
      }
    }





    /* ======================================
      AUTHENTICATE USER
      - Verifies user credentials (email/password)
      - Generates new session token
      - Updates user token in database
      - Returns true if successful
    ====================================== */
    public function authenticateUser($email, $password)
    {
      $user = $this->findByEmail($email);
      
      if ($user) {

        // check if the passwords match
        if (password_verify($password, $user->password)) {
          
          // Generate a token and insert it into the session
          $token = $user->generateToken();

          $this->setTokenToSession($token, false);

          // update token for user
          $user->token = $token;
          $this->update($user, false);

          return true;

        } else {
          return false;
        }

      } else {
        return false;
      }
    }




    /* ======================================
      FIND USER BY EMAIL
      - Searches user in database by email
      - Returns User object if found
      - Returns false if not found
    ====================================== */
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




    /* ======================================
      FIND USER BY ID
      - 
      - 
    ====================================== */
    public function findById($id)
    {
           if($id != "") {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");

        $stmt->bindParam(":id", $id);

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
      FIND USER BY TOKEN
      - Retrieves user using session token
      - Used for authentication persistence
      - Returns User object if valid
    ====================================== */
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
      - Removes token from session
      - Logs user out of the system
      - Redirects with success message
    ====================================== */
    public function destroyToken()
    {
      // remove token from session
      unset($_SESSION["token"]);
      // session_destroy();

      // Redirect and display a success message
      $this->message->setMessage(
        "You have successfully logged out!",
        "success",
        "index.php"
      );
    }



    /* ======================================
      CHANGE PASSWORD
      - 
      - 
    ====================================== */
    public function changePassword(User $user)
    {
      $stmt = $this->conn->prepare("UPDATE users 
        SET password = :password
        WHERE id = :id
      ");

      $stmt->bindParam(":password", $user->password);
      $stmt->bindParam(":id", $user->id);

      $stmt->execute();

      // Redirect and display a success message
      $this->message->setMessage(
        "Password changed successfully.",
        "success",
        "editprofile.php"
      );
    }


    /* ======================================
      CHECK ADMIN
      - Verifies if user has admin privileges
      - Returns true if admin, false otherwise
    ====================================== */
    public function isAdmin(User $user)
    {
      if($user->is_admin == 1){
        return true;
      }

      return false;
    }



    /* ======================================
      SET ADMIN
      - 
      - Should assign or remove admin role
    ====================================== */
    public function setAdmin(User $user, $isAdmin = true)
    {
      
    }
  }