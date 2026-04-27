<?php
 
  /* ======================================
     User model
  ====================================== */
  class User {
    public $id;

    public $name;

    public $lastname;

    public $email;

    public $password;

    public $image;

    public $bio;

    public $token;

    public $is_admin;

    public $is_banned;

    /* ======================================
       Get full name
    ====================================== */
    public function getFullName($user){
      return $user->name . " " . $user->lastname;
    }

    /* ======================================
       Generate token
    ====================================== */
    public function generateToken() {

      return bin2hex(random_bytes(50));
    }


    /* ======================================
       Generate password hash
    ====================================== */
    public function generatePassword($password) {

      return password_hash($password, PASSWORD_DEFAULT);
    }

    /* ======================================
       Generate image name
    ====================================== */
    public function imageGenerateName($extension = "jpg") {
      return bin2hex(random_bytes(60)) . "." . $extension;
    }
  }


  /* ======================================
     User DAO interface
  ====================================== */
  interface UserDAOInterface {

    // Build user
    public function buildUser($data);

    // Create user
    public function create(User $user, $authUser = false);

    // Update user
    public function update(User $user,$redirect = true);

    // Verify token
    public function verifyToken($protected = false);

    // Set token to session
    public function setTokenToSession($token, $redirect = true); 

    // Authenticate user
    public function authenticateUser($email, $password); 

    // Find user by email
    public function findByEmail($email); 

    // Find user by ID
    public function findById($id); 

    // Find user by token
    public function findByToken($token); 

    // Destroy token
    public function destroyToken();

    // Change password
    public function changePassword(User $user);

    // Check admin
    public function isAdmin(User $user);

    // Set admin
    public function setAdmin(User $user, $isAdmin = true);
  }