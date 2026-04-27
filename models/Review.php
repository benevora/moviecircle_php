<?php

  /* ======================================
     Review model
  ====================================== */
  class Review {
    public $id;
    public $rating;
    public $review;
    public $users_id;
    public $movies_id;
    public $user;
  }
  
  /* ======================================
     Review DAO interface
  ====================================== */
  interface ReviewDAOInterface {

    // Build review
    public function buildReview($data);

    // Create review
    public function create (Review $review);

    // Get movie reviews
    public function getMoviesReview($id);

    // Check if already reviewed
    public function hasAlreadyReviewed($id, $userId);

    // Get ratings
    public function getRatings($id);
    
  }