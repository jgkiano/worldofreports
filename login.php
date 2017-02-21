<?php require_once "includes/header.php"; ?>
<div class="content-header-industry">
	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>
</div>
<?php
if (isset($_POST["login"])) {
    $user = array();
    $user["email"] = strtolower($_POST["email"]);
    $user["password"] = $_POST["password"];
    $auth -> userLogin($user);
    $errors = $auth -> getErrors()["login"];
}
?>
<?php if($auth -> isLoggedIn()) { header(BASE_URL_REDIRECT); } ?>
<div class="center-form-container">
    <div class="login-form-container">
        <h5>Sign Into Your Account</h5>
        <?php if(isset($errors)): ?>
        <p class="error"><?php echo $errors;?></p>
        <?php endif; ?>
        <form class="" action="login.php" method="post">
            <div class="form-group">
                <input class="form-control" placeholder="Email Address" type="text" name="email" value="">
            </div>
            <div class="form-group">
                <input class="form-control" placeholder="Password" type="password" name="password" value="">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary form-control" name="login" value="Sign In">
            </div>
			<span><a href="#">Forgot password? Click here to reset</a></span>
			<span><a href="register.php">No account? Click here to register</a></span>
        </form>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
