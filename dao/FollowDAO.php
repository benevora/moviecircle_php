<?php

require_once("Follow.php");

class FollowDAO {

  private $conn;

  public function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  public function follow($followerId, $followedId) {
    $stmt = $this->conn->prepare("
      INSERT INTO follows (follower_id, followed_id)
      VALUES (:follower_id, :followed_id)
    ");

    $stmt->bindParam(":follower_id", $followerId);
    $stmt->bindParam(":followed_id", $followedId);

    return $stmt->execute();
  }
  public function getFollowers($userId) {
  $stmt = $this->conn->prepare("
    SELECT users.* FROM follows
    JOIN users ON users.id = follows.follower_id
    WHERE follows.followed_id = :id
    ORDER BY follows.created_at DESC
  ");

  $stmt->bindParam(":id", $userId);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


  public function unfollow($followerId, $followedId) {
    $stmt = $this->conn->prepare("
      DELETE FROM follows 
      WHERE follower_id = :follower_id AND followed_id = :followed_id
    ");

    $stmt->bindParam(":follower_id", $followerId);
    $stmt->bindParam(":followed_id", $followedId);

    return $stmt->execute();
  }

  public function isFollowing($followerId, $followedId) {
    $stmt = $this->conn->prepare("
      SELECT * FROM follows 
      WHERE follower_id = :follower_id AND followed_id = :followed_id
    ");

    $stmt->bindParam(":follower_id", $followerId);
    $stmt->bindParam(":followed_id", $followedId);

    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  public function countFollowers($userId) {
    $stmt = $this->conn->prepare("
      SELECT COUNT(*) FROM follows WHERE followed_id = :id
    ");

    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function countFollowing($userId) {
    $stmt = $this->conn->prepare("
      SELECT COUNT(*) FROM follows WHERE follower_id = :id
    ");

    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    return $stmt->fetchColumn();
  }
  public function getFollowing($userId) {
  $stmt = $this->conn->prepare("
    SELECT users.* FROM follows
    JOIN users ON users.id = follows.followed_id
    WHERE follows.follower_id = :id
    ORDER BY follows.created_at DESC
  ");

  $stmt->bindParam(":id", $userId);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}