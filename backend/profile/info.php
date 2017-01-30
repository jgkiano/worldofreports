<?php require_once("header.php"); ?>

<?php
if(isset($_POST["update"])) {
    $info = array();
    $info["firstname"] = $_POST["firstname"];
    $info["lastname"] = $_POST["lastname"];
    $info["country"] = $_POST["country"];
    $info["zip"] = $_POST["zip"];
    $info["address"] = $_POST["address"];
    $info["street"] = $_POST["street"];
    $info["town"] = $_POST["town"];

    $updateStatus = $auth -> updatePersonalInfo($info, $auth -> get("user_id"));

    if($updateStatus) {
        echo "change successful";
    } else {
        $errors = $auth -> getErrors();
        var_dump($errors["update-info"]);
    }
}
?>

<?php

$user = $auth -> getUserDetails($auth -> get("user_id"));

?>

<?php if(isset($user)): ?>
    <form method="post" action="">
        <div class="">
            <input type="text" name="firstname" value="<?php echo $user["user_firstname"]; ?>">
        </div>
        <div class="">
            <input type="text" name="lastname" required value="<?php echo $user["user_lastname"]; ?>">
        </div>
        <div class="">
            <input type="text" name="country" required value="<?php echo $user["user_billing_country_id"]; ?>">
        </div>
        <div class="">
            <input type="text" name="zip" required value="<?php echo $user["user_billing_zip"]; ?>">
        </div>
        <div class="">
            <input type="text" name="address" required value="<?php echo $user["user_billing_address"]; ?>">
        </div>
        <div class="">
            <input type="text" name="street" required value="<?php echo $user["user_billing_street"] ?>">
        </div>
        <div class="">
            <input type="text" name="town" required value="<?php echo $user["user_billing_town"] ?>">
        </div>
        <div class="">
            <input type="submit" name="update">
        </div>
    </form>
<?php endif; ?>

<?php require_once("footer.php") ?>
