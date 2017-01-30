<?php require_once("header.php") ?>

<?php

if(isset($_POST["change"])) {
    $oldpassword = $_POST["oldpassword"];
    $newpassword = $_POST["password"];
    $passwordconfirm = $_POST["passwordconfirm"];

    $change = $auth -> changePassword($oldpassword, $newpassword, $passwordconfirm, $auth -> get("user_id"));

    if($change) {
        echo "password changed";
    } else {
        $errors = $auth -> getErrors();
        var_dump($errors["changepassword"]);
    }
}

?>

<form action="" method="post">
    <input type="password" name="oldpassword" placeholder="old password">
    <input type="password" name="password" placeholder="new password">
    <input type="password" name="passwordconfirm" placeholder="confirm password">
    <input type="hidden" name="key" value="<?php echo $key; ?>">
    <button type="submit" name= "change" value="submit">Submit</button>
</form>



<?php require_once("footer.php") ?>
