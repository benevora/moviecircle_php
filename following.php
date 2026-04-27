<?php

/* ======================================
   Load header template
====================================== */
require_once("templates/header.php");

/* ======================================
   Load required DAOs
====================================== */
require_once("dao/UserDAO.php");
require_once("dao/FollowDAO.php");

/* ======================================
   Initialize DAOs
====================================== */
$userDao = new UserDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

/* ======================================
   Get user ID from URL
====================================== */
$userId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

/* ======================================
   Find user by ID
====================================== */
$user = $userDao->findById($userId);

/* ======================================
   Validate user existence
====================================== */
if (!$user) {
  $message->setMessage("User not found!", "error", "index.php");
  exit;
}

/* ======================================
   Get following list
====================================== */
$following = $followDao->getFollowing($userId);

?>

<!-- ======================================
     MAIN CONTENT
====================================== -->
<div id="main-container" class="container-fluid">

  <!-- Page title -->
  <h2><?= $user->name ?> is Following</h2>
  
  <!-- Following list -->
  <?php foreach($following as $u): ?>

    <a href="profile.php?id=<?= $u->id ?>" class="user-card">

      <!-- User profile image -->
      <div class="user-card-img"
           style="background-image: url('<?= $BASE_URL ?>img/users/<?= $u->image ?>')">
      </div>

      <!-- User name -->
      <div>
        <?= $u->name ?> <?= $u->lastname ?>
      </div>
    </a>

  <?php endforeach; ?>

  <!-- Empty state -->
  <?php if(count($following) === 0): ?>
    <p>Not following anyone yet.</p>
  <?php endif; ?>
</div>

/* ======================================
   Load footer template
====================================== */
<?php require_once("templates/footer.php"); ?>