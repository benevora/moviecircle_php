<?php
  /* ======================================
     HEADER TEMPLATE
     - Includes global configuration
     - Loads HTML <head>
     - Displays navigation bar
  ====================================== */

  // Load header template (HTML head + navbar)
  require_once("templates/header.php");

  require_once("dao/MovieDAO.php");

  // DAO of the movies
  $movieDao = new MovieDAO($conn, $BASE_URL);
  
  // Retrieves user search
  $q = filter_input(INPUT_GET, "q");

  $movies = $movieDao->findByTitle($q);

?>

  <!-- ========== MAIN CONTENT ========= -->
  <div id="main-container" class="container-fluid">
   
    <!-- NEW FILMS -->
    <h2 class="section-title" id="search-title">Are you looking for: <span id="search-result"><?= $q ?></span></h2>
    <p class="section-description">Search results returned based on your search.</p>
    <div class="movies-container">

      <?php foreach($movies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($movies) === 0): ?>
        <p class="empty-list">There are no films for this search, <a href="<?= $BASE_URL ?>" class="back-link">back</a>.</p>
      <?php endif; ?>

    </div>


  </div>

<?php
  /* ======================================
     FOOTER TEMPLATE
     - Closes main layout structure
     - Loads footer section
     - Includes JavaScript files
  ====================================== */

  // Load footer template (footer + scrips)
  require_once("templates/footer.php");
?>