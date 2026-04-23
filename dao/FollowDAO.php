<?php

  class FollowDAO 
  {

  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  // Follow user
  public function followUser($followerId, $followingId) {

    if ($this->isFollowing($followerId, $followingId)) {
      return; // already following, do nothing
    }


    $stmt = $this->conn->prepare("
      INSERT INTO followers (follower_id, following_id)
      VALUES (:follower_id, :following_id)
    ");

    $stmt->bindParam(":follower_id", $followerId);
    $stmt->bindParam(":following_id", $followingId);

    $stmt->execute();
  }

  // Unfollow user
  public function unfollowUser($followerId, $followingId) {
    $stmt = $this->conn->prepare("
      DELETE FROM followers 
      WHERE follower_id = :follower_id 
      AND following_id = :following_id
    ");

    $stmt->bindParam(":follower_id", $followerId);
    $stmt->bindParam(":following_id", $followingId);

    $stmt->execute();
  }

  // Check if already following
  public function isFollowing($followerId, $followingId) {
    $stmt = $this->conn->prepare("
      SELECT * FROM followers 
      WHERE follower_id = :follower_id 
      AND following_id = :following_id
    ");

    $stmt->bindParam(":follower_id", $followerId);
    $stmt->bindParam(":following_id", $followingId);

    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  // Get followers of a user
  public function getFollowers($userId) {
    $stmt = $this->conn->prepare("
      SELECT u.* FROM users u
      INNER JOIN followers f ON u.id = f.follower_id
      WHERE f.following_id = :user_id
    ");

    $stmt->bindParam(":user_id", $userId);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  // Get users a user follows
  public function getFollowing($userId) {
    $stmt = $this->conn->prepare("
      SELECT u.* FROM users u
      INNER JOIN followers f ON u.id = f.following_id
      WHERE f.follower_id = :user_id
    ");

    $stmt->bindParam(":user_id", $userId);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}
