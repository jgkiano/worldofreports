<?php require_once("header.php"); ?>
<?php
if (isset($_POST["login"])) {
    $user = array();
    $user["email"] = strtolower($_POST["email"]);
    $user["password"] = $_POST["password"];
    $auth -> userLogin($user);
    $errors = $auth -> getErrors();
}
?>
<?php if($auth -> isLoggedIn()) { header(BASE_URL_REDIRECT); }?>
    <form method="post" action="">
        <div class="">
            <input type="email" name="email" value="" placeholder="email">
        </div>
        <div class="">
            <input type="password" name="password" value="" placeholder="password">
        </div>
        <div class="">
            <input type="submit" name="login" value="login">
        </div>
    </form>
<?php require_once("footer.php"); ?>
