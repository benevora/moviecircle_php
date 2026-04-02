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
  
  $latestMovies = $movieDao->getLatestMovies();

  $actionMovies = $movieDao->getMoviesByCategory("Action");

  $comedyMovies = $movieDao->getMoviesByCategory("Comedy");

  $dramaMovies = $movieDao->getMoviesByCategory("Drama");

  $fantasy_fictionMovies = $movieDao->getMoviesByCategory("Fantasy/Fiction");

  $romanceMovies = $movieDao->getMoviesByCategory("Romance");

  $animationMovies = $movieDao->getMoviesByCategory("Animation");

?>

  <!-- ========== MAIN CONTENT ========= -->
  <div id="main-container" class="container-fluid">
   
    <!-- NEW FILMS -->
    <h2 class="section-title">New Films</h2>
    <p class="section-description">See reviews of the latest movies added to MovieCircle</p>
    <div class="movies-container">

      <?php foreach($latestMovies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($latestMovies) === 0): ?>
        <p class="empty-list">There are no movies registered yet.</p>
      <?php endif; ?>

    </div>

    <!-- ACTION FILMS -->
    <h2 class="section-title">Action</h2>
    <p class="section-description">Watch the best action movies</p>
    <div class="movies-container">

      <?php foreach($actionMovies as $movie): ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
        
      <?php if(count($actionMovies) === 0): ?>
        <p class="empty-list">There are no action movies listed yet!</p>
      <?php endif; ?>
    </div>

    <!-- COMEDY FILMS -->
    <h2 class="section-title">Comedy</h2>
    <p class="section-description">Watch the best comedy movies</p>
    <div class="movies-container">

      <?php foreach($comedyMovies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($comedyMovies) === 0): ?>
        <p class="empty-list">There are no comedy movies listed yet.</p>
      <?php endif; ?>
    </div>

    <!-- DRAMA FILMS -->
    <h2 class="section-title">Drama</h2>
    <p class="section-description">Watch the best Drama movies</p>
    <div class="movies-container">

      <?php foreach($dramaMovies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($dramaMovies) === 0): ?>
        <p class="empty-list">There are no Drama movies listed yet.</p>
      <?php endif; ?>
    </div>

    <!-- FANTASY/FICTION FILMS -->
    <h2 class="section-title">Fantasy/Fiction</h2>
    <p class="section-description">Watch the best fantasy/fiction movies</p>
    <div class="movies-container">

      <?php foreach($fantasy_fictionMovies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($fantasy_fictionMovies) === 0): ?>
        <p class="empty-list">There are no fantasy/fiction movies listed yet.</p>
      <?php endif; ?>
    </div>

    <!-- ROMANCE FILMS -->
    <h2 class="section-title">Romance</h2>
    <p class="section-description">Watch the best romance movies</p>
    <div class="movies-container">

      <?php foreach($romanceMovies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($romanceMovies) === 0): ?>
        <p class="empty-list">There are no romance movies listed yet.</p>
      <?php endif; ?>
    </div>

    <!-- ANIMATION FILMS -->
    <h2 class="section-title">Animation</h2>
    <p class="section-description">Watch the best animation movies</p>
    <div class="movies-container">

      <?php foreach($animationMovies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($animationMovies) === 0): ?>
        <p class="empty-list">There are no animation movies listed yet.</p>
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