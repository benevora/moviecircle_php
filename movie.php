<?php
  /* ======================================
     HEADER TEMPLATE
     - Includes global configuration
     - Loads HTML <head>
     - Displays navigation bar
  ====================================== */

  // Load header template (HTML head + navbar)
  require_once("templates/header.php");
  require_once("models/Movie.php");
  require_once("dao/MovieDAO.php");
  require_once("dao/ReviewDAO.php");

 

  // Get the movie id
  $id = filter_input(INPUT_GET, "id");

  $movie;

  $movieDao = new MovieDAO($conn, $BASE_URL);
  $reviewDao = new ReviewDAO($conn, $BASE_URL);
 $action = filter_input(INPUT_GET, "action");

  if($action !== "rate") {
    $action = "about";
  }

  if(empty($id)) {
    $message->setMessage(
      "The movie could not be found!",
      "error",
      "index.php"
    );
  } else {

    $movie = $movieDao->findById($id);

    // Check if the film exists
    if(!$movie) {
      $message->setMessage(
        "The movie could not be found!",
        "error",
        "index.php"
      );
    } 
  }

  // check if the film has an image
  if($movie->image == "") {
    $movie->image = "movie_cover.png";
  }

  // Check if the movie belongs to the user
  $userOwnsMovie = false;

  if(!empty($userData)) {

    if($userData->id === $movie->users_id) {
      $userOwnsMovie = true;
    }

    // Retrieve the movie reviews
    $alreadyReviewed = $reviewDao->hasAlreadyReviewed($id, $userData->id);
  }

  // Retrieve the movie reviews
  $movieReviews = $reviewDao->getMoviesReview($id);
  
?>


<!-- ========== MAIN CONTENT ========= -->
<div id="main-container" class="container-fluid">
  <div class="row align-items-end">

    <!-- LEFT SIDE -->
    <div class="offset-md-1 col-md-6 movie-container movie-left">

      <h1 class="page-title"><?= $movie->title ?></h1>

      <p class="movie-details">
        <span>Duration: <?= $movie->length ?></span>
        <span class="pipe"></span>
        <span><?= $movie->category ?></span>
        <span class="pipe"></span>
        <span><i class="fas fa-star"></i> <?= $movie->rating ?></span>
      </p>
      
      <?php if($movie->getTrailerEmbed()): ?>
        <div class="trailer-container">
          <iframe src="<?= $movie->getTrailerEmbed() ?>" frameborder="0" allowfullscreen></iframe>
        </div>
      <?php endif; ?>
    
    </div>
    
    <!-- RIGHT SIDE (POSTER) -->
    <div class="col-md-4 ">
      <img 
        src="<?= $BASE_URL ?>img/movies/<?= $movie->image ?>" 
        alt="<?= $movie->title ?>" 
        class="movie-page-image"
      >
    </div>
    
     <!-- DESCRIPTION FULL WIDTH -->
    <div class="row mt-4">
      <div class="offset-md-1 col-md-10" id="movie-description-container">

        <h3 id="movie-description">Description:</h3>

        <div class="movie-description-box">
          <?= $movie->description ?>
        </div>

      </div>
    </div>

    <div class="offset-md-1 col-md-10" id="reviews-container">
      <h3 id="reviews-title">Reviews:</h3>

      <!-- Messages ONLY when clicking RATE -->
      <?php if($action === "rate"): ?>

        <?php if(empty($userData)): ?>
          <div class="alert alert-warning mt-3">
            You need to <a href="<?= $BASE_URL ?>auth.php">login</a> to rate this movie.
          </div>

        <?php elseif($userOwnsMovie): ?>
          <div class="alert alert-info mt-3">
            You cannot rate your own movie.
          </div>

        <?php elseif($alreadyReviewed): ?>
          <div class="alert alert-success mt-3">
            You already reviewed this movie.
          </div>

        <?php endif; ?>

      <?php endif; ?>


      <!-- REVIEW FORM (ONLY when clicking Rate) -->
      <?php if($action === "rate" && !empty($userData) && !$userOwnsMovie &&!$alreadyReviewed): ?>
        
        <div class="row mt-4">
          <div class="col-md-12" id="review-form-container">
          <h4>Send your review:</h4>

          <form action="<?= $BASE_URL ?>review_process.php" method="POST">
            <input type="hidden" name="type" value="create">
            <input type="hidden" name="movies_id" value="<?= $movie->id ?>">

            <div class="form-group">
              <label>Movie rating:</label>
              <select name="rating" class="form-control">
                <option value="">Select</option>
                <option value="10">10</option>
                <option value="9">9</option>
                <option value="8">8</option>
                <option value="7">7</option>
                <option value="6">6</option>
                <option value="5">5</option>
                <option value="4">4</option>
                <option value="3">3</option>
                <option value="2">2</option>
                <option value="1">1</option>
              </select>
            </div>

            <div class="form-group">
              <label for="review">Your comment:</label>
              <textarea name="review" id="review" rows="3" class="form-control" placeholder="What did you think of the movie?"></textarea>
            </div>

            <input type="submit" class="btn card-btn" value="Send Comment">
          </form>
          </div>
        </div>

      <?php endif; ?>


      <!-- Reviews list (ALWAYS visible) -->
      <?php foreach($movieReviews as $review): ?>
        <?php require("templates/user_review.php"); ?>
      <?php endforeach; ?>

      <?php if(count($movieReviews) == 0): ?>
        <p class="empty-list">There are no comments for this movie yet...</p>
      <?php endif; ?>

    </div>
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
