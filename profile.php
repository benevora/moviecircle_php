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
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("dao/FollowDAO.php");

$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

// Logged-in user
$loggedUser = $userDao->verifyToken(false);

// Get profile ID
$id = filter_input(INPUT_GET, "id");

if ($id) {
    // Viewing someone else's profile
    $userData = $userDao->findById($id);

    if (!$userData) {
        $message->setMessage("User not found!", "error", "index.php");
    }
} else {
    // Viewing own profile
    if ($loggedUser) {
        $userData = $loggedUser;
        $id = $userData->id;
    } else {
        $message->setMessage("User not found!", "error", "index.php");
    }
}

// Full name
$fullName = $user->getFullName($userData);

// Default profile image
if ($userData->image == "") {
    $userData->image = "user.png";
}

// Movies that the user added
$userMovies = $movieDao->getMoviesByUserId($id);

// Followers / Following counts
$followersCount = $followDao->countFollowers($id);
$followingCount = $followDao->countFollowing($id);
?>

<div id="main-container" class="container-fluid">
  <div class="col-md-8 offset-md-2">
    <div class="row profile-container">
      <div class="col-md-12 about-container">

        <div class="position-relative text-center">
          <h1 class="page-title mb-0"><?= $fullName ?></h1>

          <!-- Edit Profile (only for owner) -->
          <?php if ($loggedUser && $loggedUser->id == $userData->id): ?>
            <a href="<?= $BASE_URL ?>editprofile.php" 
              class="btn btn-warning position-absolute end-0 top-50 translate-middle-y">
              Edit Profile
            </a>
          <?php endif; ?>
        </div>

        <!-- Profile Image -->
        <div id="profile-image-container" class="profile-image" 
             style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')">
        </div>

        <!-- FOLLOW BUTTON (only for other users) -->
        <?php if ($loggedUser && $loggedUser->id !== $userData->id): ?>

          <?php if ($followDao->isFollowing($loggedUser->id, $userData->id)): ?>
            <!-- Unfollow -->
            <form action="<?= $BASE_URL ?>follow_process.php" method="POST" class="mt-3">
              <input type="hidden" name="type" value="unfollow">
              <input type="hidden" name="user_id" value="<?= $userData->id ?>">
              <button class="btn btn-danger">Unfollow</button>
            </form>
          <?php else: ?>
            <!-- Follow -->
            <form action="<?= $BASE_URL ?>follow_process.php" method="POST" class="mt-3">
              <input type="hidden" name="type" value="follow">
              <input type="hidden" name="user_id" value="<?= $userData->id ?>">
              <button class="btn btn-primary">Follow</button>
            </form>
          <?php endif; ?>

        <?php endif; ?>

        <!-- FOLLOWERS / FOLLOWING LINKS -->
        <div class="mt-3">
          <a href="<?= $BASE_URL ?>followers.php?id=<?= $userData->id ?>" class="text-warning">
            Followers: <?= $followersCount ?>
          </a>

          <span class="mx-2">|</span>

          <a href="<?= $BASE_URL ?>following.php?id=<?= $userData->id ?>" class="text-warning">
            Following: <?= $followingCount ?>
          </a>
        </div>

        <h3 class="about-title">About:</h3>

        <?php if (!empty($userData->bio)): ?>
          <p class="profile-description"><?= $userData->bio ?></p>
        <?php else: ?>
          <p class="profile-description">The user hasn't written anything here yet...</p>
        <?php endif; ?>

      </div>

      <!-- MOVIES -->
      <div class="col-md-12 added-movies-container">
        <h3>Movies sent:</h3>
        <div class="movies-container">

          <?php foreach ($userMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
          <?php endforeach; ?>

          <?php if (count($userMovies) === 0): ?>
            <p class="empty-list">The user has not yet uploaded any movies.</p>
          <?php endif; ?>

        </div>
      </div>

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
