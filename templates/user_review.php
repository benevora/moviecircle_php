<?php

  /* ======================================
    Load User model
  ====================================== */
  require_once("models/User.php");

  /* ======================================
    Get full name of review author
  ====================================== */
  $userModel = new User();
  $fullName = $userModel->getFullName($review->user);

  /* ======================================
    Default user image (if none exists)
  ====================================== */
  if ($review->user->image == "") {
    $review->user->image = "user.png";
  }


?>

<!-- ======================================
  Review Component
====================================== -->
<div class="col-md-12 review">
  <div class="row">

    <!-- User avatar -->
    <div class="col-md-1">
      <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $review->user->image ?>')"></div>
    </div>

    <!-- Author details (name + rating) -->
    <div class="col-md-9 author-details-container">
      <h4 class="author-name">
        <a href="<?= $BASE_URL ?>profile.php?id=<?= $review->user->id ?>"><?= $fullName ?></a>
      </h4>
      <p><i class="fas fa-star"></i> <?= $review->rating ?></p>
    </div>

    <!-- Review comment -->
    <div class="col-md-12">
      <p class="comment-title">Comment:</p>
      <p><?= $review->review ?></p>
    </div>
  </div>
</div>