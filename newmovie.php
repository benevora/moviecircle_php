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
?>

  <!-- ========== MAIN CONTENT ========= -->
  <div id="main-container" class="container-fluid">
    <div class="new-movie-container">
      <h1 class="page-title">Add Film</h1>
      <p class="page-description">Add your review and share it with the world!</p>
      <form action="<?= $BASE_URL ?>movie_process.php" id="add-movie-form" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="type" value="create">
        <div class="form-group">
          <label for="title">Title:</label>
          <input type="text" name="title" id="title" class="form-control" placeholder="Type the title of your movie">
        </div>

        <div class="form-group">
          <label for="image">Image:</label>
          <input type="file" name="image" id="image" class="form-control-file">
        </div>

        <div class="form-group">
          <label for="length">Duration:</label>
          <input type="text" name="length" id="length" class="form-control" placeholder="Enter the movie's duration">
        </div>

        <div class="form-group">
          <label for="category">Category:</label>
          <select name="category" id="category" class="form-control">
            <option value="Action">Action</option>
            <option value="Drama">Drama</option>
            <option value="Comedy">Comedy</option>
            <option value="Fantasy / Fiction">Fantasy / Fiction</option>
            <option value="Romance">Romance</option>
            <option value="Animation">Animation</option>
          </select>
        </div>


        <div class="form-group">
          <label for="trailer">Trailer:</label>
          <input type="url" name="trailer" id="trailer" class="form-control-file" placeholder="Insert the trailer link">
        </div>

        <div class="form-group">
          <label for="description">Description:</label>
          <textarea name="description" id="description" rows="5" class="form-control" placeholder="Describe the film"></textarea>
        </div>

        <input type="submit" class="btn card-btn" value="Add film">
      </form>
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