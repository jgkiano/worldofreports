<?php

require_once("../classes/Authentication.php");

Authentication::startSession();

$auth = new Authentication();

if(!$auth -> isLoggedIn() && count($auth -> get("user_id")) <= 0) {
    header(BASE_URL_REDIRECT);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>World of records</title>
    <link rel="stylesheet" id="font-awesome-style-css" href="http://phpflow.com/code/css/bootstrap3.min.css" type="text/css" media="all">
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
</head>
<body>
    <?php if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {?>
        <a href="http://localhost/worldofreports/backend/profile/">Profile</a>
        <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="reset.php">Forgot Password?</a>
        <?php } ?>
