<?php

require_once("templates/header.php");
require_once("dao/UserDAO.php");
require_once("dao/FollowDAO.php");

$userDao = new UserDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

$userId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

$user = $userDao->findById($userId);

if (!$user) {
  $message->setMessage("User not found!", "error", "index.php");
  exit;
}

$following = $followDao->getFollowing($userId);

?>

<div id="main-container" class="container-fluid">
  <h2><?= $user->name ?> is Following</h2>

  <?php foreach($following as $u): ?>

    <a href="profile.php?id=<?= $u->id ?>" class="user-card">
      <div class="user-card-img"
           style="background-image: url('<?= $BASE_URL ?>img/users/<?= $u->image ?>')">
      </div>

      <div>
        <?= $u->name ?> <?= $u->lastname ?>
      </div>
    </a>

  <?php endforeach; ?>

  <?php if(count($following) === 0): ?>
    <p>Not following anyone yet.</p>
  <?php endif; ?>
</div>

<?php require_once("templates/footer.php"); ?>