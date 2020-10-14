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
<div class="jumbotron bg-dark">
    <div class="container">
        <div class="row mt-5 mb-3">
            <div class="col-2"></div>
            <div class="col-8">
                <h1 class="leap text-light mx-auto">Share your news. Share your opinions.</h1>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <a href="new_story.php" class="btn btn-primary btn-lg mb-3"
            <?php
            // make sure user is logged in
            if (!isset($_SESSION)) {
                session_start();
            }
            if (!isset($_SESSION["username"])) {
                echo " hidden";
            }
            ?>
        >New Story</a>
    </div>
</div>

<!-- story list -->
<div class="container mb-5">
    <?php
    // fetch stories by popularity
    $mysqli = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt = $mysqli->prepare("select id, title, content, user_id, likes from stories order by likes desc");
    $stmt->execute();
    $stmt->bind_result($story_id, $title, $content, $user_id, $likes);


    while ($stmt->fetch()) {
        //look up username
        $mysqli2 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
        $stmt2 = $mysqli2->prepare("select username from users where id=?");
        $stmt2->bind_param('i', $user_id);
        $stmt2->execute();
        $stmt2->bind_result($username);
        $stmt2->fetch();
        $stmt2->close();

        // print stories
        printf('<div class="row mb-3"><div class="card col-12"><div class="row">
        <i class="fas fa-heart fa-2x col-1 align-self-center" style="color: orangered">%s</i>
        <div class="card-body text-truncate col-10">
            <h3 class="card-title"><a href="story_detail.php?id=%s">%s</a></h3>
            <p class="card-text">%s</p>
            <p class="font-weight-light text-secondary">Posted by %s</p>
        </div>
    </div></div></div>',
            $likes, $story_id, $title, $content, $username
        );
    }
    $stmt->close();
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



<!-- TODO: check FIEO-->
<!-- TODO:CSRF tokens are passed when creating, editing, and deleting comments and stories (5 points) ?????-->
<!-- TODO: final code reformatting and review-->
