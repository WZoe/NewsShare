<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['username']);
unset($_SESSION['token']);
session_destroy();
header("Location: index.php");
?>