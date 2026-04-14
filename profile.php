<?php
  /* ======================================
     HEADER TEMPLATE
     - Includes global configuration
     - Loads HTML <head>
     - Displays navigation bar
  ====================================== */

  // Load header template (HTML head + navbar)
require_once("templates/header.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("dao/FollowDAO.php");

$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

// Logged-in user (may be null)
$loggedUser = $userDao->verifyToken(false);

// Get profile user ID
$id = filter_input(INPUT_GET, "id");

if (!$id) {
  $message->setMessage("User not found.", "error", "index.php");
  exit;
}

// User whose profile we are viewing
$profileUser = $userDao->findById($id);

if (!$profileUser) {
  $message->setMessage("User not found.", "error", "index.php");
  exit;
}

// Full name
$fullName = $user->getFullName($profileUser);

// Default profile image
if ($profileUser->image == "") {
  $profileUser->image = "user.png";
}

// Followers / Following counts
$followers = $followDao->countFollowers($profileUser->id);
$following = $followDao->countFollowing($profileUser->id);

// Check if logged user follows this profile
$isFollowing = false;

if ($loggedUser && $loggedUser->id !== $profileUser->id) {
  $isFollowing = $followDao->isFollowing($loggedUser->id, $profileUser->id);
}

// Movies by this user
$userMovies = $movieDao->getMoviesByUserId($profileUser->id);
?>

<div id="main-container" class="container-fluid">
  <div class="row justify-content-center">
    
    <div class="col-md-8">

      <div class="row profile-container">

        <!-- ABOUT SECTION -->
        <div class="col-md-12 about-container text-center">
          <h1 class="page-title"><?= $fullName ?></h1>

          <div 
            id="profile-image-container" 
            class="profile-image mx-auto"
            style="background-image: url('<?= $BASE_URL ?>img/users/<?= $profileUser->image ?>'); 
                   width: 150px; height: 150px; background-size: cover; border-radius: 50%;">
          </div>

          <h3 class="about-title mt-3">About:</h3>

          <?php if (!empty($profileUser->bio)): ?>
            <p class="profile-description"><?= $profileUser->bio ?></p>
          <?php else: ?>
            <p class="profile-description">
              The user hasn't written anything here yet...
            </p>
          <?php endif; ?>

          <!-- FOLLOWERS / FOLLOWING -->
          <p>
            <strong>
              <a href="<?= $BASE_URL ?>followers.php?id=<?= $profileUser->id ?>">
                <?= $followers ?>
              </a>
            </strong> Followers
          </p>

          <p>
            <strong>
              <a href="<?= $BASE_URL ?>following.php?id=<?= $profileUser->id ?>">
                <?= $following ?>
              </a>
            </strong> Following
          </p>

          <!-- FOLLOW / UNFOLLOW BUTTON -->
          <?php if ($loggedUser && $loggedUser->id !== $profileUser->id): ?>
            <form action="<?= $BASE_URL ?>follow_process.php" method="POST" class="mt-3">
              <input type="hidden" name="user_id" value="<?= $profileUser->id ?>">

              <?php if ($isFollowing): ?>
                <input type="hidden" name="type" value="unfollow">
                <button class="btn btn-danger">Unfollow</button>
              <?php else: ?>
                <input type="hidden" name="type" value="follow">
                <button class="btn btn-primary">Follow</button>
              <?php endif; ?>
            </form>
          <?php endif; ?>

        </div>

        <!-- MOVIES SECTION -->
        <div class="col-md-12 added-movies-container mt-4">
          <h3>Movies sent:</h3>

          <div class="row movies-container">
            <?php foreach ($userMovies as $movie): ?>
              <div class="col-md-4 mb-3">
                <?php require("templates/movie_card.php"); ?>
              </div>
            <?php endforeach; ?>

            <?php if (count($userMovies) === 0): ?>
              <div class="col-12">
                <p class="empty-list">
                  The user has not yet uploaded any movies.
                </p>
              </div>
            <?php endif; ?>
          </div>

        </div>

      </div>
    </div>

  </div>
</div>

<?php require_once("templates/footer.php"); ?>
