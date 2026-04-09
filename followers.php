<?php
require_once("templates/header.php");
require_once("dao/UserDAO.php");
require_once("dao/FollowDAO.php");

$userDao = new UserDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

// Logged-in user
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

// Get followers
$followers = $followDao->getFollowers($profileUser->id);
?>

<div id="main-container" class="container-fluid pt-5">
  <h1><?= $profileUser->name ?>'s Followers</h1>

  <div class="row mt-4">

    <?php if (count($followers) === 0): ?>
      <p class="empty-list">This user has no followers yet.</p>
    <?php endif; ?>

    <?php foreach ($followers as $follower): ?>

      <?php
        // image
        if ($follower["image"] == "") {
          $follower["image"] = "user.png";
        }
      ?>

      <div class="col-md-4 mb-4">
        <div class="card p-3">

          <div class="profile-image-container review-image mb-3"
               style="background-image: url('<?= $BASE_URL ?>img/users/<?= $follower["image"] ?>')">
          </div>

          <h4>
            <a href="<?= $BASE_URL ?>profile.php?id=<?= $follower["id"] ?>">
              <?= $follower["name"] . " " . $follower["lastname"] ?>
            </a>
          </h4>

        </div>
      </div>

    <?php endforeach; ?>

  </div>
</div>

<?php require_once("templates/footer.php"); ?>
