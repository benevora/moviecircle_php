<?php
function toggleFollow($db, $follower, $following) {
    // Check following
    $query = $db->prepare("SELECT * FROM follows WHERE follower_id = ? AND following_id = ?");
    $query->execute([$follower, $following]);

    if ($query->rowCount() > 0) {
        // Unfollow
        $delete = $db->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?");
        $delete->execute([$follower, $following]);
        return "unfollowed";
    } else {
        // Follow
        $insert = $db->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)");
        $insert->execute([$follower, $following]);
        return "followed";
    }
}
?>
