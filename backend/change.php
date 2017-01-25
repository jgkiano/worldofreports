<?php require_once("header.php"); ?>

<?php

if(isset($_GET["r"]) && count($_POST) == 0) {
    $key = $_GET["r"];
    if(strlen($key) != 32) {
        $isKeyValid = false;
        $errors["reset"] = "Reset Key is not valid";
    } else {
        $isKeyValid = $auth -> isResetKeyValid($key);
        $errors =  $auth -> getErrors();
    }
}

if(isset($_POST["resetpass"])) {
    $newPassword = $_POST["password"];
    $confimPass = $_POST["passwordConfirm"];
    $key = $_POST["key"];
    if(strlen($key) != 32) {
        $errors["reset"] = "Reset Key is not valid";
    } else {
        $isKeyValid = $auth -> isResetKeyValid($key);
        $errors =  $auth -> getErrors();
        if($isKeyValid && count($errors) == 0) {
            $confirmReset = $auth -> resetPassword($newPassword, $confimPass, $key);
            $errors =  $auth -> getErrors();
            var_dump($errors);
        }
    }
}

?>
<?php if(!isset($confirmReset)): ?>
	<?php if (!$isKeyValid): ?>
		<?php echo $errors["reset"]; ?>
	<?php else: ?>
		<form action="change.php" method="post">
			<input type="password" name="password" placeholder="new password">
			<input type="password" name="passwordConfirm" placeholder="confirm password">
			<input type="hidden" name="key" value="<?php echo $key; ?>">
			<button type="submit" name= "resetpass" value="submit">Submit</button>
		</form>
	<?php endif; ?>
<?php elseif ($confirmReset): ?>
	<p>Congrats you've successfully changed your password</p>
<?php else: ?>
	<p>Changed failed</p>
<?php endif; ?>


<?php require_once("footer.php"); ?>