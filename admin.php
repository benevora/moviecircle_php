<?php
  require_once("templates/header.php");
  require_once("globals.php");
  require_once("config/db.php");
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");
  require_once("models/Message.php");

  $message = new Message($BASE_URL);

  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  $user = $userDao->verifyToken(true);

  // Check admin
  if(!$userDao->isAdmin($user)) {
      $message->setMessage(
          "Access denied. Admins only.",
          "error",
          "index.php"
      );
      exit;
  }

  // DATA
  $movies = $movieDao->getAllMovies();
  $users = $userDao->getAllUsers();
  

  
?>

<!-- ========== MAIN CONTENT ========= -->
<div id="main-container" class="container-fluid">

  <h2 class="section-title">Admin Dashboard</h2>
  <p>Welcome, <?= $user->name ?>!</p>

  <!-- ================= MOVIES ================= -->
  <h2 class="section-title">Movies</h2>
  <p class="section-description">Manage all movies in the system</p>

  <div class="col-md-12" id="movies-dashboard">
    <div class="admin-section">
      <table class="table table-striped table-hover align-middle">
        <thead>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Added By</th>
          <th scope="col" class="actions-column">Action</th>
        </thead>
    
        <tbody>
          
          <?php foreach($movies as $i => $movie): ?>
            <tr>
              <td><?= $i + 1 ?></td>
  
              <td class="movie-title-cell">
                <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="table-movie-title">
                  <?= $movie->title ?>
                </a>
              </td>
  
              <td>
                <a href="<?= $BASE_URL ?>profile.php?id=<?= $movie->users_id ?>" class="table-user-link">
                  <?= $movie->user_name ?>
                </a>
              </td>
  
              <td class="actions-column">
                <div class="action-buttons">
  
                  <form action="<?= $BASE_URL ?>movie_process.php" method="POST">
                    <input type="hidden" name="type" value="delete">
                    <input type="hidden" name="id" value="<?= $movie->id ?>">
  
                    <button type="submit" class="delete-btn">
                      <i class="fas fa-times"></i> Delete
                    </button>
                  </form>
  
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>

  <!-- ================= USERS ================= -->
  <h2 class="section-title">Users</h2>
  <p class="section-description">
  <div class="col-md-12" id="user-dashboard">
      View, manage, and control user access across the platform
    </p>
    
    <div class="admin-section">
      <table class="table table-striped table-hover align-middle admin-table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">User Name</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col" class="actions-column">Action</th>
          </tr>
        </thead>
  
        <tbody>
          <?php foreach($users as $i => $u): ?>
            <tr>
              <td><?= $i + 1 ?></td>
  
              <td class="movie-title-cell">
                <a href="<?= $BASE_URL ?>profile.php?id=<?= $u->id ?>" class="table-user-link">
                  <?= $u->name . " " . $u->lastname ?>
                </a>
              </td>
  
              <td><?= $u->email ?></td>
  
              <td>
                <?php if($u->is_banned): ?>
                  <span class="status-badge status-banned">Banned</span>
                <?php else: ?>
                  <span class="status-badge status-active">Active</span>
                <?php endif; ?>
              </td>
  
              <td class="actions-column">
                <div class="action-buttons">
  
                  <?php if($u->is_banned): ?>
                    <a href="unban_user.php?id=<?= $u->id ?>" class="btn-ban">
                      <i class="fas fa-user-check"></i> Unban
                    </a>
                  <?php else: ?>
                    <a href="ban_user.php?id=<?= $u->id ?>" class="btn-unban">
                      <i class="fas fa-user-slash"></i> Ban
                    </a>
                  <?php endif; ?>
  
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
  

</div>

<?php require_once("templates/footer.php"); ?>