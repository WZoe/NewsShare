<?php
if (!$_SESSION) {
    session_start();
}
// make sure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
} else {
    $story_id = $_GET["story_id"];
    $comment_id = $_GET["comment_id"];

    // retrieve story owner
    $mysqli1 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt1 = $mysqli1->prepare("SELECT user_id FROM comments WHERE id=?");
    $stmt1->bind_param('i', $comment_id);
    $stmt1->execute();
    $stmt1->bind_result($owner_id);
    $stmt1->fetch();
    $stmt1->close();
    // check if current user has right to delete this comment
    if ($_SESSION['id'] == $owner_id) {
        $mysqli = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
        $stmt = $mysqli->prepare("DELETE FROM comments WHERE id=?");
        $stmt->bind_param('i', $comment_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: story_detail.php?id=" . $story_id);
}
?>