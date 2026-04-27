<?php

  /* ======================================
    Default movie image
  ====================================== */
  if(empty($movie->image)) {
    $movie->image = "movie_cover.png";
  }

?>


<!-- ======================================
  Movie Card Component
====================================== -->
<div class="card movie-card">

  <!-- Movie cover image (background) -->
  <div class="card-img-top" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')"></div>
  <div class="card-body">

    <!-- Movie rating display -->
    <p class="card-rating">
      <i class="fas fa-star"></i>
      <span class="rating"><?= $movie->rating ?></span>
    </p>

    <!-- Movie title with link to details page -->
    <h5 class="card-title">
      <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>"><?= $movie->title ?></a>
    </h5>

    <!-- Action buttons (Rate + About) -->
    <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>&action=rate" class="btn btn-primary rate-btn">Rate</a>
    <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="btn btn-primary card-btn">About</a>

  </div>
</div>