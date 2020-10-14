<?php
session_start();
// make sure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
} else {
    $story_id = (int)$_GET["story_id"];
    $author_id = (int)$_GET["author_id"];

    if ($author_id != $_SESSION['id']) {
        //follow author: insert into followers table
        $mysqli2 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
        // insert ignore won't allow duplicates
        $stmt2 = $mysqli2->prepare("INSERT IGNORE INTO followers (follower, being_followed) VALUES (?,?)");
        $stmt2->bind_param('ii', $_SESSION['id'], $author_id);
        $stmt2->execute();
        $stmt2->close();
    }
    header("Location: story_detail.php?id=" . $story_id);
}
?>