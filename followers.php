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
<nav class="navbar navbar-dark navbar-expand-sm bg-dark  fixed-top">
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
<div class="container mt-5">
    <div class="row">
        <h1 class="mt-5 mb-5 ml-5 col-12"><?php
            if (!$_SESSION) {
                session_start();
            }
            // make sure user is logged in
            if (isset($_SESSION['username'])) {
                echo htmlentities($_SESSION['username']);
            } else {
                header("Location: index.php");
            } ?></h1>
    </div>
    <div class="row">
        <ul class="nav nav-tabs nav-fill col-12">
            <li class="nav-item">
                <a class="nav-link " href="profile.php">My Stories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="comments.php">My Comments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">Followers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="following.php">Following</a>
            </li>
        </ul>
    </div>
</div>

<!-- followers list -->
<div class="container mb-5">
    <?php
    // fetch stories by popularity
    $mysqli = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
    $stmt = $mysqli->prepare("select follower from followers where being_followed=?");
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($follower);


    while ($stmt->fetch()) {
        //fetch user_being_followed username
        $mysqli2 = new mysqli('ec2-54-191-166-77.us-west-2.compute.amazonaws.com', '503', '503', 'news_site');
        $stmt2 = $mysqli2->prepare("select username from users where id=?");
        $stmt2->bind_param('i', $follower);
        $stmt2->execute();
        $stmt2->bind_result($follower_username);
        $stmt2->fetch();
        $stmt2->close();

        // print stories
        printf('<div class="row mb-3">
<div class="card col-12">
<div class="row">
        <div class="col-1"></div>
        <div class="card-body text-truncate col-10">
            <h3 class="card-title">%s</h3>
        </div>
    </div></div></div>',
            htmlentities($follower_username)
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