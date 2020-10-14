<?php
if (!$_SESSION) {
    session_start();
}
// make sure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
} else {
    $story_id = (int)$_GET["story_id"];

    //retrieve story likes count
    $mysqli1 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt1 = $mysqli1->prepare("select likes from stories where id=?");
    $stmt1->bind_param('i', $story_id);
    $stmt1->execute();
    $stmt1->bind_result($likes);
    $stmt1->fetch();
    $stmt1->close();

    //update likes of the story as $likes+1
    $likes = $likes + 1;
    $mysqli2 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt2 = $mysqli2->prepare("UPDATE stories SET likes=? WHERE id=?");
    $stmt2->bind_param('ii', $likes, $story_id);
    $stmt2->execute();
    $stmt2->close();

    header("Location: story_detail.php?id=" . $story_id);
}
?>