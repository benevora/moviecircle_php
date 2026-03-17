<?php
  require_once("globals.php");
  require_once("config/db.php");
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("models/Message.php");

  $message = new Message($BASE_URL);

  $userDao = new UserDAO($conn, $BASE_URL);

  $user = $userDao->verifyToken(true);

  // Check if user is admin
  if(!$userDao->isAdmin($user)) {
    $message->setMessage(
        "Access denied. Admins only.",
        "error",
        "index.php"
    );
    exit;
  }

  require_once("templates/header.php");
?>


<!-- ========== MAIN CONTENT ========= -->
<div id="main-container" class="container-fluid">
  <h1>Admin Dashboard</h1>
  <p>Welcome, <?= $user->name ?>! You have admin access.</p>
</div>

<?php require_once("templates/footer.php");