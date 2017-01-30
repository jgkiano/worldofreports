<?php require_once("header.php") ?>

<?php
if(isset($_POST["email"])) {
    $email = strtolower($_POST["email"]);
    if($auth -> changeEmail($auth -> get("user_id"), $email)) {
        
    } else {
        $errors = $auth -> getErrors();
        if(isset($errors["change-email"])) {
            echo $errors["change-email"];
        }
    }
}
?>
<p>changing your email address will affect your login credentials and you'll be required to confirm the new email</p>
<form class="" action="" method="post">
    <div class="form-group">
        <input type="text" name="email" placeholder="new email address">
    </div>
    <button type="submit" class="btn btn-default" name="button">Change Email</button>
</form>
<?php require_once("footer.php") ?>
