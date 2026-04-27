<?php

  require_once ("models/User.php");
  require_once ("models/Message.php");

  class UserDAO implements UserDAOInterface 
  {

    private $conn;
    private $url;
    private $message;


    /* ======================================
      Initialize DB connection, base URL, and message system
    ====================================== */
    public function __construct(PDO $conn, $url)
    {
      $this->conn = $conn;
      $this->url = $url;
      $this->message = new Message($url);
    }


    /* ======================================
      Convert database row into User object
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
      Create a new user (optionally auto-login)
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

      // Auto login after registration
      if ($authUser) 
      {
        $this->setTokenToSession($user->token);
      }

    }


    /* ======================================
      Update user data
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
        
        // Redirects to the user's profile after update
        $this->message->setMessage(
          "Data updated successfully",
          "success",
          "editprofile.php"
        );
      }

    }


    /* ======================================
      Check session token and return logged user
    ====================================== */
    public function verifyToken($protected = false)
    {
      if (!empty($_SESSION["token"])) {

        // get the session token
        $token = $_SESSION["token"];

        $user = $this->findByToken($token);

        if ($user) {

          // Block banned users
          if ($user->is_banned) {

            // destroy session
            $this->destroyToken();

            // redirect with message
            $this->message->setMessage(
              "Your account has been banned.",
              "error",
              "index.php"
            );
            exit;
          }

          return $user;

        } else if($protected) {
          $this->message->setMessage(
            "Please authenticate to access this page.",
            "error",
            "index.php"
          );
        }

      } else if($protected) {
        $this->message->setMessage(
          "Please authenticate to access this page.",
          "error",
          "index.php"
        );
      }
    }


    /* ======================================
      Store authentication token in session
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
      Authenticate user login
    ====================================== */
    public function authenticateUser($email, $password)
    {
      $user = $this->findByEmail($email);
      
      if ($user) {

        // check if the passwords match
        if (password_verify($password, $user->password)) {
          
          // Block banned users
          if ($user->is_banned) {
            $this->message->setMessage(
              "Your account has been banned.",
              "error",
              "auth.php"
            );
            exit;
          }

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
      Find user by email
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
      Find user by ID
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
      Find user by session token
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
      Logout user (remove session token)
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
      Change user password
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
      Check if user is admin
    ====================================== */
    public function isAdmin(User $user)
    {
      if($user->is_admin == 1){
        return true;
      }

      return false;
    }



    /* ======================================
     (Not implemented yet)
    ====================================== */
    public function setAdmin(User $user, $isAdmin = true)
    {
      
    }


    /* ======================================
     Get all users
    ====================================== */
    public function getAllUsers() {

       $stmt = $this->conn->prepare("SELECT * FROM users");
       $stmt->execute();

       return $stmt->fetchAll(PDO::FETCH_OBJ);
    }



    /* ======================================
     Ban user
    ====================================== */
    public function banUser($id) {

      $stmt = $this->conn->prepare("UPDATE users 
        SET is_banned = 1 
        WHERE id = :id
      ");

      $stmt->bindParam(":id", $id);
      $stmt->execute();

      $this->message->setMessage(
        "User banned successfully.",
        "success",
        "back"
      );
    }


    /* ======================================
     Unban user
    ====================================== */
    public function unbanUser($id) {

      $stmt = $this->conn->prepare("UPDATE users
        SET is_banned = 0 
        WHERE id = :id
      ");

      $stmt->bindParam(":id", $id);
      $stmt->execute();

      $this->message->setMessage(
        "User unbanned successfully.",
        "success",
        "back"
      );
    }


  }