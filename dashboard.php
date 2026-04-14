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
  $movieDao = new MovieDAO($conn, $BASE_URL);

  /* ======================================
   PROTECTED PAGE ACCESS
   - Requires the user to be authenticated
   - If no valid session token is found,
     the user is redirected to index.php
  ====================================== */

  // Verify user token (true = protected page)
  $userData = $userDao->verifyToken(true);

  $userMovies = $movieDao->getMoviesByUserId($userData->id);

?>


<!-- ========== MAIN CONTENT ========= -->
<div id="main-container" class="container-fluid">
  <h2 class="section-title">My Films</h2>
  <p class="section-description">Add or update the information for the movies you submitted</p>

  <div class="col-md-12" id="add-movie-container">
    <a href="<?= $BASE_URL ?>newmovie.php" class="btn card-btn">
      <i class="fas fa-plus"></i> Add Film
    </a>
  </div>

  <div class="col-md-12" id="movies-dashboard">
    
    <table class="table table-striped table-hover align-middle">

      <thead>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Rate</th>
        <th scope="col" class="actions-column">Action</th>
      </thead>

      <tbody>
        <?php $counter = 1; ?>
        <?php foreach($userMovies as $movie): ?>

          <tr>
            <td class="counter" scope="row"><?= $counter ?></td>

            <td class="movie-title-cell">
              <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="table-movie-title">
                <?= $movie->title ?>
              </a>
            </td>

            <td><i class="fas fa-star"></i><span class="rating-number"><?= $movie->rating ?></span></td>

            <td class="actions-column">
              <div class="action-buttons">

                <a href="<?= $BASE_URL ?>editmovie.php?id=<?= $movie->id ?>" class="edit-btn" title="Edit">
                  <i class="far fa-edit"></i>Edit
                </a>

                <form action="<?= $BASE_URL ?>movie_process.php" method="POST">
                  <input type="hidden" name="type" value="delete">
                  <input type="hidden" name="id" value="<?= $movie->id ?>">
                  <button type="submit" class="delete-btn" title="Delete">
                    <i class="fas fa-times"></i>Delete
                  </button>
                </form>

              </div>
            </td>
          </tr>
          <?php $counter++; ?>
        <?php endforeach; ?>

      </tbody>

    </table>
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