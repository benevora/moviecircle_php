<?php
  /* ======================================
    GLOBAL CONFIGURATION
    - starts sessions
    - Defines Base URL
  ====================================== */
  require_once("globals.php");
  
  /* ======================================
    DATABASE CONNECTION
    - Creates PDO connection instance
    - Makes #conn available globally
  ====================================== */
  require_once("config/db.php");


  $flashMessage = [];

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Character encoding -->
  <meta charset="UTF-8">

  <!-- Responsive layout for mobile devices -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Page title -->
  <title>MovieCircle</title>

  <!-- Website favicon -->
  <link rel="short icon" href="<?= $BASE_URL ?>img/moviecircle.ico">

  <!-- Bootstrap css (layouts + components) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.css" integrity="sha512-zylce7fP6h4usg536JBTRj2rt7q22Z0qicHSlgSK53Irtfkz37ate3KCQ59du+aXZV6R3yyL2X1LyGKBEUMZaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Font Awesome (icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Custom project styles -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>css/styles.css?">
</head>
<body>

  <!-- ================= HEADER ================= -->
  <header>

    <!-- Main navigation bar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg">

      <!-- Logo + Project Name -->
      <a href="<?= $BASE_URL ?>" class="navbar-brand"> 
        <img  src="<?= $BASE_URL ?>img/logo.svg" 
              alt="MovieCircle" 
              id="logo">
        <span id="moviecircle-title">MovieCircle</span>
      </a>

      <!-- Mobile toggle button -->
      <button 
   `  class="navbar-toggler" 
      type="button" 
      data-bs-toggle="collapse"
      data-bs-target="#navbar" 
      aria-controls="navbar" 
      aria-expanded="false" 
      aria-label="Toggle navigation"
      >
      <i class="fas fa-bars"></i>
      </button>

      <!-- Movie search form -->
      <form action="" 
            method="GET" 
            id="search-form" 
            class="d-flex align-items-center">

        <!-- search input -->
        <input 
        type="search"
        name="q" 
        id="search" 
        class="form-control me-2" 
        placeholder="Search movies..." 
        aria-label="search">

        <!-- Search button -->
        <button class="btn my-2 my-sm-0" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </form>
    
      <!-- Collapsible navigation links -->
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav ms-auto">

          <!-- Authentication link -->
          <li class="nav-item">
            <a href="<?= $BASE_URL ?>auth.php" class="nav-link"> Login / Register </a>
          </li>

        </ul>
      </div>
    </nav>
  </header>

  <!-- ================= FLASH MESSAGES =================  -->
  <?php if(!empty($flashMessage["msg"])): ?>
    <div class="msg-container">

      <!-- Message text + dynamic type (success, error, warning) -->
      <p class="msg <?= $flashMessage["type"] ?>">
        <?= $flashMessage["msg"] ?>
      </p>

    </div>
  <?php endif; ?>