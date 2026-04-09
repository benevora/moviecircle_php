<?php
require_once("templates/header.php");
require_once("dao/UserDAO.php");
require_once("dao/FollowDAO.php");

$userDao = new UserDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

// Logged-in user (optional)
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

// Get following list
$following = $followDao->getFollowing($profileUser->id);
?>

<div id="main-container" class="container-fluid pt-5">
  <h1><?= $profileUser->name ?> is Following</h1>

  <div class="row mt-4">

    <?php if (count($following) === 0): ?>
      <p class="empty-list">This user is not following anyone yet.</p>
    <?php endif; ?>

    <?php foreach ($following as $followed): ?>

      <?php
        if ($followed["image"] == "") {
          $followed["image"] = "user.png";
        }
      ?>

      <div class="col-md-4 mb-4">
        <div class="card p-3">

          <div class="profile-image-container review-image mb-3"
               style="background-image: url('<?= $BASE_URL ?>img/users/<?= $followed["image"] ?>')">
          </div>

          <h4>
            <a href="<?= $BASE_URL ?>profile.php?id=<?= $followed["id"] ?>">
              <?= $followed["name"] . " " . $followed["lastname"] ?>
            </a>
          </h4>

        </div>
      </div>

    <?php endforeach; ?>

  </div>
</div>

<?php require_once("templates/footer.php"); ?>
