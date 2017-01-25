
<?php require_once("header.php"); ?>
<?php

if(isset($_POST["email"])) {
    $email = strtolower($_POST["email"]);
    $auth -> forgotPassword($email);
    var_dump($auth -> getErrors());
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <form action="" method="post">
            <input type="email" name="email" placeholder="email">
            <button type="submit" value="submit">Submit</button>
        </form>
    </body>
</html>
