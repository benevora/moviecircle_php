<?php

  /* ======================================
    Load header template
  ====================================== */
  require_once("templates/header.php");

  /* ======================================
    Load global configuration
  ====================================== */
  require_once("globals.php");

  /* ======================================
    Database connection
  ====================================== */
  require_once("config/db.php");

  /* ======================================
    Load models and DAOs
  ====================================== */
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");
  require_once("models/Message.php");
  
  /* ======================================
    Initialize message system
  ====================================== */
  $message = new Message($BASE_URL);

  /* ======================================
    Initialize DAOs
  ====================================== */
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  /* ======================================
    Verify authentication (protected page)
  ====================================== */
  $user = $userDao->verifyToken(true);


  /* ======================================
    Check admin access
  ====================================== */
  if(!$userDao->isAdmin($user)) {
      $message->setMessage(
          "Access denied. Admins only.",
          "error",
          "index.php"
      );
      exit;
  }


  /* ======================================
    Load dashboard data
  ====================================== */
  $movies = $movieDao->getAllMovies();
  $users = $userDao->getAllUsers();
  

  
?>

<!-- ======================================
  MAIN ADMIN DASHBOARD CONTAINER
====================================== -->
<div id="main-container" class="container-fluid">

  <h2 class="section-title">Admin Dashboard</h2>
  <p>Welcome, <?= $user->name ?>!</p>

  <!-- ================= MOVIES SECTION ================= -->
  <h2 class="section-title">Movies</h2>
  <p class="section-description">Manage all movies in the system</p>

  <div class="col-md-12" id="movies-dashboard">
    <div class="admin-section">

      <!-- Movies table -->
      <table class="table table-striped table-hover align-middle">
        <thead>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Added By</th>
          <th scope="col" class="actions-column">Action</th>
        </thead>
    
        <tbody>
          
          <!-- Loop movies -->
          <?php foreach($movies as $i => $movie): ?>
            <tr>
              <td><?= $i + 1 ?></td>

              <!-- Movie title -->
              <td class="movie-title-cell">
                <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="table-movie-title">
                  <?= $movie->title ?>
                </a>
              </td>

              <!-- Movie owner -->
              <td>
                <a href="<?= $BASE_URL ?>profile.php?id=<?= $movie->users_id ?>" class="table-user-link">
                  <?= $movie->user_name ?>
                </a>
              </td>
  
              <!-- Delete action -->
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

  <!-- ================= USERS SECTION ================= -->
  <h2 class="section-title">Users</h2>
  <p class="section-description">
  <div class="col-md-12" id="user-dashboard">
      View, manage, and control user access across the platform
    </p>
    
    <div class="admin-section">

      <!-- Users table -->
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

          <!-- Loop users -->
          <?php foreach($users as $i => $u): ?>
            <tr>
              <td><?= $i + 1 ?></td>

              <!-- User name -->
              <td class="movie-title-cell">
                <a href="<?= $BASE_URL ?>profile.php?id=<?= $u->id ?>" class="table-user-link">
                  <?= $u->name . " " . $u->lastname ?>
                </a>
              </td>
            
              <!-- Email -->
              <td><?= $u->email ?></td>
  
              <!-- Status -->
              <td>
                <?php if($u->is_banned): ?>
                  <span class="status-badge status-banned">Banned</span>
                <?php else: ?>
                  <span class="status-badge status-active">Active</span>
                <?php endif; ?>
              </td>
  
              <!-- Actions -->
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