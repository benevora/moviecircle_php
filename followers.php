Enable desktop notifications for Gmail.
   OK  No thanks
1 of many
following file
Inbox

Ben Evora
Attachments
4:09 PM (3 minutes ago)
to me

 5 Attachments
  •  Scanned by Gmail
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

$followers = $followDao->getFollowers($userId);

?>

<div id="main-container" class="container-fluid">
  <h2><?= $user->name ?>'s Followers</h2>

  <?php foreach($followers as $u): ?>

    <a href="profile.php?id=<?= $u->id ?>" class="user-card">
      <div class="user-card-img"
           style="background-image: url('<?= $BASE_URL ?>img/users/<?= $u->image ?>')">
      </div>

      <div>
        <?= $u->name ?> <?= $u->lastname ?>
      </div>
    </a>

  <?php endforeach; ?>

  <?php if(count($followers) === 0): ?>
    <p>No followers yet.</p>
  <?php endif; ?>
</div>

<?php 
  require_once("templates/footer.php"); 
?>