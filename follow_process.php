<?php

require_once("templates/header.php");
require_once("dao/UserDAO.php");
require_once("dao/FollowDAO.php");

$userDao = new UserDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

// Get logged user safely
$loggedUser = $userDao->verifyToken(false);

if (!$loggedUser) {
  $message->setMessage("You must be logged in!", "error", "index.php");
  exit;
}

$followerId = $loggedUser->id;

// Get POST data safely
$followingId = filter_input(INPUT_POST, "following_id", FILTER_VALIDATE_INT);
$action = filter_input(INPUT_POST, "action");

if (!$followingId || !$action) {
  $message->setMessage("Invalid request!", "error", "index.php");
  exit;
}

// Prevent self-follow
if ($followerId == $followingId) {
  $message->setMessage("You cannot follow yourself!", "error", "index.php");
  exit;
}

// Execute action
if ($action === "follow") {
  $followDao->followUser($followerId, $followingId);
} else if ($action === "unfollow") {
  $followDao->unfollowUser($followerId, $followingId);
}

// Redirect back to profile
header("Location: profile.php?id=" . $followingId);
exit;