<?php
require_once("classes/Registration.php");

if(isset($_POST["register"])) {

    $user = array();

    $user["email"] = strtolower($_POST["email"]);

    $user["firstname"] = $_POST["firstname"];

    $user["lastname"]  = $_POST["lastname"];

    $user["password"] = $_POST["password"];

    $user["passwordRepeat"] = $_POST["passwordRepeat"];

    $register = new Register();

    $register -> register($user);

    $errors = $register -> getErrors();

    var_dump($errors);

    $register = null;

}

?>
<?php require_once("header.php"); ?>

<?php if($auth -> isLoggedIn()) {header("location: http://localhost/wrv2/backend/");}?>
    <form method="post" action="">
        <div class="">
            <input type="email" name="email" required placeholder="email">
        </div>
        <div class="">
            <input type="text" name="firstname" required placeholder="First Name">
        </div>
        <div class="">
            <input type="text" name="lastname" required placeholder="Last Name">
        </div>
        <div class="">
            <input type="password" name="password" required placeholder="Password">
        </div>
        <div class="">
            <input type="password" name="passwordRepeat" required placeholder="Repeat Password">
        </div>
        <div class="">
            <input type="submit" name="register">
        </div>
    </form>

<?php require_once("footer.php"); ?>
