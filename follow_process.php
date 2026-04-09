<?php

require_once("globals.php");
require_once("config/db.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/FollowDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$followDao = new FollowDAO($conn);

$userData = $userDao->verifyToken(true);

$type = filter_input(INPUT_POST, "type");
$targetId = filter_input(INPUT_POST, "user_id");

if (!$targetId) {
  $message->setMessage("Invalid user.", "error", "back");
}

if ($type === "follow") {

  if ($followDao->isFollowing($userData->id, $targetId)) {
    $message->setMessage("You already follow this user.", "error", "back");
  }

  $followDao->follow($userData->id, $targetId);

  $message->setMessage("You are now following this user!", "success", "back");

} elseif ($type === "unfollow") {

  $followDao->unfollow($userData->id, $targetId);

  $message->setMessage("You unfollowed this user.", "success", "back");

} else {
  $message->setMessage("Invalid action.", "error", "index.php");
}
