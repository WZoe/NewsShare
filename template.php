<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- The following lines are cited from https://getbootstrap.com/docs/4.5/getting-started/introduction/ -->
    <!-- Bootstrap CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
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
            }
            else {
                echo '<li class="nav-item"><a class="nav-link mr-3" href="profile.php">'.htmlentities($_SESSION["username"]).'\'s Profile</a></li>';
                echo '<li class="nav-item"><a class="btn btn-light" href="logout.php">Log Out</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>
<!-- Write Code Here -->





<footer class="jumbotron">
    <p class="lead mx-auto footer">Zoe Wang & Eimee Yang, 2020/10</p>
</footer>
<!-- The following line is cited from https://getbootstrap.com/docs/4.5/getting-started/introduction/ and https://fontawesome.com/-->
<!-- font awesome -->
<script src="https://kit.fontawesome.com/b53fd5134a.js" crossorigin="anonymous"></script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<!-- Citation End -->
</body>
</html>