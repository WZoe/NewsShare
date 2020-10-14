<?php
    if (!$_SESSION) {
        session_start();
    }
    // make sure user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
    } else {
        $story_id = $_GET["story_id"];

        // retrieve story owner
        $mysqli1 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
        $stmt1 = $mysqli1->prepare("SELECT user_id FROM stories WHERE id=?");
        $stmt1->bind_param('i', $story_id);
        $stmt1->execute();
        $stmt1->bind_result($owner_id);
        $stmt1->fetch();
        $stmt1->close();
        // check if current user has right to delete this story
        if ($_SESSION['id']==$owner_id) {
            // first delete comments associated with the story
            $mysqli2 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
            $stmt2 = $mysqli2->prepare("DELETE FROM comments WHERE story_id=?");
            $stmt2->bind_param('i', $story_id);
            $stmt2->execute();
            $stmt2->close();

            // then safely delete story
            $mysqli3 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
            $stmt3 = $mysqli3->prepare("DELETE FROM stories WHERE id=?");
            $stmt3->bind_param('i', $story_id);
            $stmt3->execute();
            $stmt3->close();
        }
        
        header("Location: index.php");
    }
?>