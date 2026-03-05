<?php

    /*
    ========================================
    USER MODEL
    - Represents a user entity in the system
     - Stores user-related data from database
     - Used by DAO to create and manipulate users
    ========================================
    */

    class User {

  // Unique user ID (Primary Key)
  public $id;

  // User first name
  public $name;

  // User last name
  public $lastname;

  // User email (used for login)
  public $email;

  // User password
  public $password;

  // Profile image filename/path
  public $image;

  // Short biography or description
  public $bio;

  // Authentication token (used for sessions/login)
  public $token;

  // Admin role flag (1 = admin, 0 = regular user)
  public $is_admin;

  // Ban status flag (1 = banned, 0 = active user)
  public $is_banned;

    }

    /*
    ========================================
     USER DATA ACCESS INTERFACE (DAO)
     - Defines all database operations
       related to the User entity
     - Forces consistency in UserDAO implementation
    ========================================
    */

    interface UserDAOInterface {

    // Converts database array into a User object
    public function buildUser($data);

    // Inserts new user into database
    // $authUser = true logs the user in after registration
    public function create(User $user, $authUser = false);

    // Updates existing user information
    public function update(User $user);

    // Verifies if a user token is valid
    // $protected = true blocks access if not authenticated
    public function verifyToken($protected = false);

    // Saves authentication token in session
    // $redirect = true redirects after login
    public function setTokenToSession($token, $redirect = true);

    // Authenticates user using email and password
    public function authenticateUser($email, $password);

    // Finds user by email address
    public function findByEmail($email);

    // Finds user by ID
    public function findById($id);

    // Finds user by authentication token
    public function findByToken($token);

    // Updates user password (after hashing)
    public function changePassword(User $user);

    // Checks if user has admin privileges
    public function isAdmin(User $user);

    // Grants or removes admin privileges
    public function setAdmin(User $user, $isAdmin = true);

    }