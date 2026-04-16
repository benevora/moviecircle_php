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

  // Get selected category
  $category = "";

  if(isset($_GET["category"])) {
    $category = $_GET["category"];
  }

  // Decide which movies to load
  if($category == "") {
    $movies = $movieDao->getAllMoviesBuilt();
  } else {
    $movies = $movieDao->getMoviesByCategory($category);
  }
?>

  <!-- ========== MAIN CONTENT ========= -->
  <div id="main-container" class="container-fluid">
   
    <!-- NEW FILMS -->
    <form method="GET" action="" id="home-page-form">
      <h2 class="section-title">
        <?= $category == "" ? "Posted Films" : $category . " Movies" ?>
      </h2>
      <label for="category">Filter by category:</label>
      <select id="category" name="category" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <option value="Action" <?= $category == "Action" ? "selected" : "" ?>>Action</option>
        <option value="Comedy" <?= $category == "Comedy" ? "selected" : "" ?>>Comedy</option>
        <option value="Drama" <?= $category == "Drama" ? "selected" : "" ?>>Drama</option>
        <option value="Fantasy/Fiction" <?= $category == "Fantasy/Fiction" ? "selected" : "" ?>>Fantasy/Fiction</option>
        <option value="Romance" <?= $category == "Romance" ? "selected" : "" ?>>Romance</option>
        <option value="Animation" <?= $category == "Animation" ? "selected" : "" ?>>Animation</option>
      </select>
    </form>

    <?php if($category != ""): ?>
      <a href="index.php" class="clear-filter">Clear Filter</a>
    <?php endif; ?>

    <p class="section-description">
      <?= $category == "" 
        ? "Discover all movies available on MovieCircle" 
        : "Showing " . $category . " movies" ?>
    </p>
    <div class="movies-container">

      <?php foreach($movies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>

      <?php if(count($movies) === 0): ?>
        <p class="empty-list">
          <?= $category == "" 
              ? "There are no movies registered yet." 
              : "There are no movies in this category yet." ?>
        </p>
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