<?php require_once "includes/header.php"; ?>
<?php
require_once("classes/Reports.php");
//okay check if we have a report id in post or session, if not you shouldn't be accessign this page
if(isset($_POST["report_id"]) || $auth -> get("buy-report")!=null) {
    //if we get a post request with report id that must mean user wants that report so we verify the report and override the session variable
    if(isset($_POST["report_id"])) {
        $report_id = $_POST["report_id"];
        $report = new Reports();
        if(!$report -> reportExists($report_id)) {
            $auth -> destroy("buy-report");
            $report_id = null;
            header(BASE_URL_REDIRECT);
        } else {
            $auth -> set("buy-report",$report_id);
        }
    }
} else {
    header(BASE_URL_REDIRECT);
}

?>
<div class="content-header-industry">
	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>
</div>
<?php
if (isset($_POST["login"]) && $auth -> get("buy-report") != null) {
    $user = array();
    $user["email"] = strtolower($_POST["login-email"]);
    $user["password"] = $_POST["login-password"];
    $auth -> userLogin($user);
    $errors = $auth -> getErrors();
    if(array_key_exists("login",$errors)) {
        $errors = $errors["login"];
    }
}
?>
<?php if($auth -> isLoggedIn() && $auth -> get("buy-report") != null) { header("location: checkout.php"); } ?>
<div class="container login-register">
    <h5>User Information</h5>
    <hr>
    <div class="row">
        <div class="col-lg-6">
            <h3>New User</h3>
            <hr class="blue-hr">
            <form class="" action="register.php" method="post">
                <div class="form-group">
                    <label for="register-email">Email</label>
                    <input type="text" name="checkout-email" class="form-control" placeholder="Email Address" value="">
                </div>
                <div class="form-group">
                    <input type="submit" name="checkout" class="btn btn-primary" value="continue">
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            <h3>Returning User</h3>
            <hr class="blue-hr">
            <form class="" action="precheckout.php" method="post">
                <?php if(isset($errors)): ?>
                <p class="error"><?php echo $errors;?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="text" name="login-email" class="form-control" placeholder="Email Address" value="">
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" name="login-password" class="form-control" placeholder="Password" value="">
                </div>
                <div class="form-group">
                    <input type="submit" name="login" class="btn btn-primary" value="continue">
                </div>
                <span><a href="#">Forgot your password? Click here to reset</a></span>
            </form>
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
