<?php
require_once("classes/Registration.php");

if(isset($_POST["register"])) {

    $user = array();

    $user["email"] = strtolower($_POST["email"]);

    $user["firstname"] = ucfirst(strtolower($_POST["firstname"]));

    $user["lastname"]  = ucfirst(strtolower($_POST["lastname"]));

    $user["country"]  = $_POST["country"];

    $user["zip"]  = $_POST["zip"];

    $user["address"]  = ucwords(strtolower($_POST["address"]));

    $user["street"]  = ucwords(strtolower($_POST["street"]));

    $user["town"]  = ucwords(strtolower($_POST["town"]));

    $user["password"] = $_POST["password"];

    $user["passwordRepeat"] = $_POST["passwordRepeat"];

    $register = new Register();

    $isRegistered = $register -> register($user);

    $errors = $register -> getErrors();

    var_dump($errors);

    $register = null;

}

?>
<?php require_once("header.php"); ?>

<?php if($auth -> isLoggedIn()) {header("location: http://localhost/wrv2/backend/");}?>

<?php if(!isset($isRegistered)): ?>
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
            <input type="text" name="country" required placeholder="Country">
        </div>
        <div class="">
            <input type="text" name="zip" required placeholder="zip postal code">
        </div>
        <div class="">
            <input type="text" name="address" required placeholder="billing address">
        </div>
        <div class="">
            <input type="text" name="street" required placeholder="street address">
        </div>
        <div class="">
            <input type="text" name="town" required placeholder="town">
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
<?php elseif ($isRegistered): ?>
    <p>Registration successful, go confirm the link</p>
<?php else: ?>
    <p>Registration did not happen</p>
<?php endif; ?>

<?php require_once("footer.php"); ?>
