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
   Get followers list
====================================== */
$followers = $followDao->getFollowers($userId);

?>

<!-- ======================================
     MAIN CONTENT
====================================== -->
<div id="main-container" class="container-fluid">

  <!-- Page title -->
  <h2><?= $user->name ?>'s Followers</h2>

  <!-- Followers list -->
  <?php foreach($followers as $u): ?>

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
  <?php if(count($followers) === 0): ?>
    <p>No followers yet.</p>
  <?php endif; ?>
</div>

<?php 
  /* ======================================
   Load footer template
  ====================================== */
  require_once("templates/footer.php"); 
?>