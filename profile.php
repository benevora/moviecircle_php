<?php
require_once("templates/header.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("dao/FollowDAO.php");

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

$profileUser = $userDao->findById($id);

if (!$profileUser) {
  $message->setMessage("User not found.", "error", "index.php");
  exit;
}

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

<div id="main-container" class="container-fluid pt-5">
  <div class="row justify-content-center">

    <!-- LEFT COLUMN: PROFILE INFO -->
    <div class="col-md-4 text-center">

      <div class="profile-image-container" 
           style="background-image: url('<?= $BASE_URL ?>img/users/<?= $profileUser->image ?>')">
      </div>

      <h1><?= $profileUser->name . " " . $profileUser->lastname ?></h1>

      <p class="page-description"><?= $profileUser->bio ?: "This user has no bio yet." ?></p>

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
        <form action="<?= $BASE_URL ?>follow_process.php" method="POST">
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

    <!-- RIGHT COLUMN: USER MOVIES -->
    <div class="col-md-8">
      <h2 class="section-title">Movies by <?= $profileUser->name ?></h2>

      <div class="movies-container">
        <?php foreach($userMovies as $movie): ?>
          <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>

        <?php if (count($userMovies) === 0): ?>
          <p class="empty-list">This user has not added any movies yet.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<?php require_once("templates/footer.php"); ?>
