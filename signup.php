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
<div class="jumbotron">
    <div class="container mt-5">
        <div class="row">
            <h1>Sign Up</h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row row-content align-items-center">
        <div class="col-4"></div>
        <div class="col-4">
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username </label>
                    <input class="form-control" type="text" name="username"/>
                </div>
                <div class="form-group">
                    <label for="password">Password </label>
                    <input class="form-control" type="password" name="password"/>
                </div>
                <input class="btn btn-primary btn-block" type="submit" value="Log in"/><br>
            </form>
            <?php
            //signup function
            // see if already logged in
            if (isset($_SESSION["id"])) {
                header("Location: index.php");
            }
            if (isset($_POST["password"]) && isset($_POST["username"])) {
                //read username from input
                if ($_POST["password"] == "" || $_POST["username"] == "") {
                    // no input, alert user
                    echo '<div class="alert alert-danger" role="alert">
                    Username and password can\'t be empty!</div>';
                } else {
                    $username = $_POST["username"];
                    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);

                    $mysqli = new mysqli('localhost', '503', '503', 'news_site');
                    // look up database to see if there is existing user
                    $stmt = $mysqli->prepare("select id from users where username=?");
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        // user exist, alert
                        echo '<div class="alert alert-danger" role="alert">
                    This username already exists!</div>';
                    } else {
                        //create new entry.
                        $stmt1 = $mysqli->prepare("insert into users (username, password) values (?,?)");
                        if ($stmt1) {
                            $stmt1->bind_param('ss', $username, $password);
                            $stmt1->execute();
                            $id = $mysqli->insert_id;
                            $stmt1->close();

                            //set session
                            if (!isset($_SESSION)) {
                                session_start();
                            } else {
                                session_destroy();
                                session_start();
                            }
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $username;
                            header("Location: index.php");
                        }
                        $stmt->close();

                    }
                }
            }
            ?>
            <p>Already have an account? <a href="login.php">Log back in here.</a></p>
        </div>
    </div>
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