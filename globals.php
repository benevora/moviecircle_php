<?php
 /* ======================================
     SESSION INITIALIZATION
     - Starts a new session or resumes existing one
     - Required for login system, Admin access control, User authentication etc.
  ====================================== */
  session_start();

  /* ==========================================================
      BASE URL CONFIGURATION (Cross-platform / Windows-safe)
      - Dynamically builds the base URL of the project
      - Ensures CSS, JS, and image links work correctly
      - Handles Windows, Linux, and Mac environments
  ========================================================== */
  $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
  $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Get current project folder
$folder = basename(__DIR__); // safer than dirname($_SERVER['REQUEST_URI'])
$BASE_URL = "http://{$serverName}/{$folder}/";

// Now your links like <link href="<?php echo $BASE_URL ?>assets/css/styles.css"> will work