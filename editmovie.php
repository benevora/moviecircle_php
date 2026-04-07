<?php
  /* ======================================
     HEADER TEMPLATE
     - Includes global configuration
     - Loads HTML <head>
     - Displays navigation bar
  ====================================== */

  // Load header template (HTML head + navbar)
  require_once("templates/header.php");

  // checks if the user is authenticated
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");

  $user = new User();
  $userDao = new UserDAO($conn, $BASE_URL);

  /* ======================================
   PROTECTED PAGE ACCESS
   - Requires the user to be authenticated
   - If no valid session token is found,
     the user is redirected to index.php
  ====================================== */

  // Verify user token (true = protected page)
  $userData = $userDao->verifyToken(true);

  $movieDao = new MovieDAO($conn, $BASE_URL);

  $id = filter_input(INPUT_GET, "id");

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

?>

  <!-- ========== MAIN CONTENT ========= -->
  <div id="main-container" class="container-fluid">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6 offset-md-1">
        <h1><?= $movie->title ?></h1>
        <p class="page-description">Change the movie details in the form below</p>
        <form id="edit-movie-form" action="<?= $BASE_URL ?>movie_process.php" method="POST" enctype="multipart/form-data">

          <input type="hidden" name="type" value="update">
          <input type="hidden" name="id" value="<?= $movie->id ?>">
          <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Type the title of your movie" value="<?= $movie->title ?>">
          </div>

          <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image" id="image" class="form-control-file">
          </div>

          <div class="form-group">
            <label for="length">Duration:</label>
            <input type="text" name="length" id="length" class="form-control" placeholder="Enter the movie's duration" value="<?= $movie->length ?>">
          </div>

          <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" id="category" class="form-control">
              <option value="Action" <?= $movie->category === "Action" ? "selected" : "" ?>>Action</option>
              <option value="Drama" <?= $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
              <option value="Comedy" <?= $movie->category === "Comedy" ? "selected" : "" ?>>Comedy</option>
              <option value="Fantasy/Fiction" <?= $movie->category === "Fantasy/Fiction" ? "selected" : "" ?>>Fantasy/Fiction</option>
              <option value="Romance" <?= $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
              <option value="Animation" <?= $movie->category === "Animation" ? "selected" : "" ?>>Animation</option>
            </select>
          </div>


          <div class="form-group">
            <label for="trailer">Trailer:</label>
            <input type="url" name="trailer" id="trailer" class="form-control-file" placeholder="Insert the trailer link" value="<?= $movie->trailer ?>">
          </div>

          <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="5" class="form-control" placeholder="Describe the film..."><?= $movie->description ?></textarea>
          </div>

          <input type="submit" class="btn card-btn" value="Edit film">


        </form>
      </div>
      <div class="col-md-3">
        <img 
        src="<?= $BASE_URL ?>img/movies/<?= $movie->image ?>" 
        alt="<?= $movie->title ?>" 
        class="movie-page-image"
        >
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