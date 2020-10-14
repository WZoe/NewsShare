<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- The following lines are cited from https://getbootstrap.com/docs/4.5/getting-started/introduction/ -->
    <!-- Bootstrap CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Citation End -->
    <link rel="stylesheet" href="main.css">

    <title>News Share</title>
</head>
<body>
<nav class="navbar navbar-dark navbar-expand-sm bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand mr-auto" href="index.php"><i class="far fa-2x fa-newspaper">News Share</i></a>
        <ul class="navbar-nav justify-content-end">
            <?php
            session_start();
            if (!isset($_SESSION["username"])) {
                echo '<li class="nav-item"><a class="btn btn-primary mr-3" href="signup.php">Sign Up</a></li>';
                echo '<li class="nav-item"><a class="btn btn-light" href="login.php">Log In</a></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link mr-3" href="profile.php">' . htmlentities($_SESSION["username"]) . '\'s Profile</a></li>';
                echo '<li class="nav-item"><a class="btn btn-light" href="logout.php">Log Out</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>
<!-- Write Code Here -->
<?php
//get story and content
if (!isset($_GET['id'])) {
    header('Location: index.php');
} else {
    $story_id = $_GET['id'];

    //retrieve story
    $mysqli = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt = $mysqli->prepare("select title, content, link, user_id, likes from stories where id=?");
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->bind_result($title, $content, $link, $user_id, $likes);
    $stmt->fetch();
    $stmt->close();

    //retrieve comments
    $mysqli2 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt2 = $mysqli2->prepare("select id, content, user_id, likes from comments where story_id=? order by likes desc");
    $stmt2->bind_param('i', $story_id);
    $stmt2->execute();
    $stmt2->bind_result($comment_id, $comment_content, $comment_user_id, $comment_likes);

    //retrieve author
    $mysqli3 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt3 = $mysqli3->prepare("select username from users where id=?");
    $stmt3->bind_param('i', $user_id);
    $stmt3->execute();
    $stmt3->bind_result($author);
    $stmt3->fetch();
    $stmt3->close();
}
?>

<!--title-->
<div class="jumbotron">
    <div class="container mt-5">
        <div class="row justify-content-center mb-3">
            <h1 class="text-center"><?php echo htmlentities($title) ?></h1></div>
        <div class="row justify-content-center"><h3 class="text-muted">By: <?php echo htmlentities($author) ?></h3>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="btn-group btn-group-lg">
                <a href="<?php echo 'like_story.php?story_id=' . $story_id ?>" class="btn btn-danger <?php
                // check if logged in
                if (!isset($_SESSION['id'])) {
                    echo 'disabled';
                }
                ?>"><i class="fas fa-heart"><?php echo $likes; ?></i></a>
                <a href="<?php echo htmlentities($link) ?>" target="_blank" class="btn btn-primary">Link to News</a>
                <?php
                // check if user is author
                if (!isset($_SESSION)) {
                    session_start();
                }
                if (!isset($_SESSION['id'])) {
                    session_destroy();
                } else {
                    if ($_SESSION['id'] != $user_id) {
                        $mysqli4 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
                        $stmt4 = $mysqli4->prepare("SELECT COUNT(follower) FROM followers WHERE follower=? AND being_followed=?");
                        $stmt4->bind_param('ii', $_SESSION['id'], $user_id);
                        $stmt4->execute();
                        $stmt4->bind_result($relationship);
                        $stmt4->fetch();
                        $stmt4->close();
                        if ($relationship == 0) {
                            echo '<a href="follow_author.php?author_id=' . $user_id . '&story_id=' . $story_id . '" class="btn btn-dark">Follow Author</a>';
                        } else {
                            echo '<a class="btn btn-dark">Following</a>';
                        }
                    } else {
                        echo '<a href="edit_story.php?story_id=' . $story_id . '" class="btn btn-secondary">Edit</a>';
                        // form for deleting story
                        echo '<form class="col-12" action="delete_story.php" method="POST">';
                        echo '<input type="hidden" name="story_id" value="' . $story_id . '" />';
                        echo '<input type="hidden" name="token" value="' . $_SESSION['token'] . '" />';
                        echo '<input class="btn btn-secondary" type="submit" value="Delete"/><br>';
                        echo '</form>';
                    }
                }

                ?>

            </div>
        </div>
    </div>
</div>
<!--body-->
<div class="container mb-5">
    <div class="row">
        <p><?php
            echo nl2br($content);
            ?></p>
    </div>
</div>
<!--comments-->
<div class="container mb-5">
    <div class="row border-bottom mb-3">
        <h2 class="col-10">Comments</h2>
        <a href="new_comment.php?id=<?php echo $story_id; ?>" class="col-2 btn btn-primary"
            <?php
            // check if logged in
            if (!isset($_SESSION['id'])) {
                echo 'hidden';
            }
            ?>
        >Add Comments</a>
    </div>
    <?php
    //load comments
    while ($stmt2->fetch()) {
        //fetch commenter username
        $mysqli5 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
        $stmt5 = $mysqli5->prepare("select username from users where id=?");
        $stmt5->bind_param('i', $comment_user_id);
        $stmt5->execute();
        $stmt5->bind_result($commenter);
        $stmt5->fetch();
        $stmt5->close();

        // print comments
        printf('<div class="row mb-3"><div class="card col-12"><div class="row">
        <i class="fas fa-heart fa-2x col-1 align-self-center" style="color: orangered">%s</i>
        <div class="card-body text-truncate col-9">
            <h3 class="card-title">%s</h3>
            <p class="card-text">%s</p>
        </div>'
            , $comment_likes, $commenter, $comment_content);
        echo '<div class="col-2 btn-group-vertical align-self-center">';
        // check if user is author
        if (isset($_SESSION['id'])) {
            echo '<a href="like_comment.php?story_id=' . $story_id . '&comment_id=' . $comment_id . '" class="btn btn-danger"><i class="fas fa-heart"></i></a>';
            if ($_SESSION['id'] == $comment_user_id) {
                echo '<a href="edit_comment.php?story_id=' . $story_id . '&comment_id=' . $comment_id . '" class="btn btn-secondary">Edit</a>';
                // form for deleting comment
                echo '<form class="col-12" action="delete_comment.php" method="POST">';
                    echo '<input type="hidden" name="story_id" value="' . $story_id . '" />';
                    echo '<input type="hidden" name="comment_id" value="' . $comment_id . '" />';
                    echo '<input type="hidden" name="token" value="' . $_SESSION['token'] . '" />';
                    echo '<input class="btn btn-secondary" type="submit" value="Delete"/><br>';
                echo '</form>';
            }
        }
        echo '</div></div></div></div>';
    }
    $stmt2->close();
    ?>
</div>


<footer class="jumbotron">
    <p class="lead mx-auto footer">Zoe Wang & Eimee Yang, 2020/10</p>
</footer>
<!-- The following line is cited from https://getbootstrap.com/docs/4.5/getting-started/introduction/ and https://fontawesome.com/-->
<!-- font awesome -->
<script src="https://kit.fontawesome.com/b53fd5134a.js" crossorigin="anonymous"></script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
<!-- Citation End -->
</body>
</html>