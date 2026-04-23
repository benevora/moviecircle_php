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
  require_once("dao/FollowDAO.php");

  $user = new User();
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);
  $followDao = new FollowDAO($conn);


  // Get logged-in user
  $loggedUser = $userDao->verifyToken(false);

  // receive the user ID
  $id = filter_input(INPUT_GET, "id");

  if ($id) {

    // Profile from another user
    $userData = $userDao->findById($id);

    if (!$userData) {
      $message->setMessage("User not found!", "error", "index.php");
    }

  } else {

    // Profile from logged-in user
    if ($loggedUser) {
      $userData = $loggedUser;
      $id = $userData->id;
    } else {
      $message->setMessage("User not found!", "error", "index.php");
    }
  }

  $followers = $followDao->getFollowers($userData->id);
  $following = $followDao->getFollowing($userData->id);
  
  // Get user's full name using model method
  $fullName = $user->getFullName($userData);

  // Set default image if user has no profile picture
  if ($userData->image == "") {
    $userData->image = "user.png";
  }

  // Movies that the user added
  $userMovies = $movieDao->getMoviesByUserId($id);


?>
<div id="main-container" class="container-fluid">
  <div class="row profile-layout">
    <div class="col-md-9">
      <div class="row profile-container">
        <div class="col-md-12 about-container">

          <div class="position-relative text-center">
            <h1 class="page-title mb-0"><?= $fullName ?></h1>

           

          </div>

           <div id="profile-image-container" class="profile-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')"></div>
           <h3 class="about-title">About:</h3>
           <?php if(!empty($userData->bio)): ?>
            <p class="profile-description"><?= $userData->bio ?></p>
            <?php else: ?>
              <p class="profile-description">The user hasn't written anything here yet...</p>
           <?php endif; ?>
        </div>
        <div class="col-md-12 added-movies-container">
          <h3>Movies Posted:</h3>
          <div class="movies-container">
            <?php foreach($userMovies as $movie): ?>
            <?php require("templates/movie_card.php") ?>
            <?php endforeach; ?>
            <?php if(count($userMovies) === 0): ?>
              <p class="empty-list">The user has not yet uploaded any movies.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 profile-sidebar">

      <?php if($loggedUser && $loggedUser->id == $userData->id): ?>
        <a href="<?= $BASE_URL ?>editprofile.php" class="profile-side-link">
          Edit Profile
        </a>
      <?php endif; ?>

      <?php if($loggedUser && $loggedUser->id != $userData->id): ?>

      <?php if($followDao->isFollowing($loggedUser->id, $userData->id)): ?>

        <form method="POST" action="<?= $BASE_URL ?>follow_process.php"
              class="profile-side-link">
          
          <input type="hidden" name="follower_id" value="<?= $loggedUser->id ?>">
          <input type="hidden" name="following_id" value="<?= $userData->id ?>">
          <input type="hidden" name="action" value="unfollow">

          <button type="submit" class="follow-link">
            Unfollow
          </button>

        </form>

      <?php else: ?>

        <form method="POST" action="<?= $BASE_URL ?>follow_process.php"
              class="profile-side-link">

          <input type="hidden" name="follower_id" value="<?= $loggedUser->id ?>">
          <input type="hidden" name="following_id" value="<?= $userData->id ?>">
          <input type="hidden" name="action" value="follow">

          <button type="submit" class="unfollow-link">
            Follow
          </button>

        </form>

      <?php endif; ?>

      <?php endif; ?>

      <a href="<?= $BASE_URL ?>followers.php?id=<?= $userData->id ?>" class="profile-side-link">
        <strong><?= count($followers) ?></strong> Followers
      </a>

      <a href="<?= $BASE_URL ?>following.php?id=<?= $userData->id ?>" class="profile-side-link">
        <strong><?= count($following) ?></strong> Following
      </a>

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