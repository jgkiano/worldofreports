<?php require_once "includes/header.php"; ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="content-header-industry">
	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>
</div>
<?php
require_once("classes/Registration.php");

require_once("captcha/recaptchalib.php");

$countryObj = new Country();

$countries = $countryObj -> getAllCountries();

$countryArray = array();

foreach ($countries as $country) {
	array_push($countryArray, $country["name"]);
}

$countryArray = json_encode($countryArray);

if(isset($_POST["register"])) {
	// check secret key

	$reCaptcha = new ReCaptcha(GOOGLE_CAPTCHA_SECRECT_KEY);
	$resp = null;
	if ($_POST["g-recaptcha-response"]) {
		$resp = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]);
	} else {
    	$errors["captcha"] = "Confirm you're not a robot";
	}
	if(isset($resp) && $resp -> success) {
		$user = array();

	    $user["email"] = strtolower($_POST["email"]);

	    $user["firstname"] = ucfirst(strtolower($_POST["firstname"]));

	    $user["lastname"]  = ucfirst(strtolower($_POST["lastname"]));

	    $user["country"]  = $_POST["country"];

	    $user["zip"]  = $_POST["zip"];

	    $user["address"]  = ucwords(strtolower($_POST["address"]));

	    $user["phone"]  = $_POST["phone"];

	    $user["town"]  = ucwords(strtolower($_POST["town"]));

	    $user["password"] = $_POST["password"];

	    $user["passwordRepeat"] = $_POST["confirmpass"];

	    $register = new Register();

	    $isRegistered = $register -> register($user);

	    $errors = $register -> getErrors();

		if(array_key_exists("register",$errors)) {
			$errors = $errors["register"];
		}

	    $register = null;
	} else {
    	$errors["captcha"] = "Confirm you're not a robot";
	}
}

?>
<?php if($auth -> isLoggedIn()) { header(BASE_URL_REDIRECT); } ?>
<?php if(!isset($isRegistered) || !$isRegistered): ?>
<div class="register-form-container">

<script src='https://www.google.com/recaptcha/api.js'></script>

<form class="register-form" action="register.php" method="post">
    <h5>Register For World Of Reports</h5>
    <?php if(isset($errors)): ?>
        <p class="error">Hmm, seems like you have a couple of errors</p>
    <?php endif; ?>
    <hr>
    <div class="form-group row">
        <div class="col-lg-6">
            <label for="firstname">First Name<span class="red">*</span></label>
            <input name= "firstname" type="text"class="form-control <?php if(isset($errors["firstname"])){echo "danger";} ?>" placeholder="First Name" name="" value="<?php if (isset($user["firstname"])) {echo $user["firstname"];} ?>">
            <?php if(isset($errors["firstname"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["firstname"]; ?></small>
            <?php endif; ?>

        </div>
        <div class="col-lg-6">
            <label for="lastname">Last Name<span class="red">*</span></label>
            <input name="lastname" type="text"class="form-control <?php if(isset($errors["lastname"])){echo "danger";} ?>" placeholder="Last Name" name="" value="<?php if (isset($user["lastname"])) {echo $user["lastname"];} ?>">
            <?php if(isset($errors["lastname"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["lastname"]; ?></small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <label for="email">Email Address<span class="red">*</span></label>
            <input type="email" class="form-control <?php if(isset($errors["email"])){echo "danger";} ?>" placeholder="Email Address" name="email" value="<?php if (isset($user["email"])) {echo $user["email"];} elseif(isset($_POST["checkout-email"])) {echo $_POST["checkout-email"];} ?>">
            <?php if(isset($errors["email"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["email"]; ?></small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-6">
            <label for="country">Country<span class="red">*</span></label>
            <input name= "country" id="tags" type="text"class="form-control <?php if(isset($errors["country"])){echo "danger";} ?>" placeholder="Country" name="" value="<?php if (isset($user["country"])) {echo $user["country"];} ?>">
            <?php if(isset($errors["country"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["country"]; ?></small>
            <?php endif; ?>
        </div>
        <div class="col-lg-6">
            <label for="zip">ZIP / Postal Code<span class="red">*</span></label>
            <input name="zip" type="text"class="form-control <?php if(isset($errors["zip"])){echo "danger";} ?>" placeholder="ZIP / Postal Code" value="<?php if (isset($user["zip"])) {echo $user["zip"];} ?>">
            <?php if(isset($errors["zip"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["zip"]; ?></small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <label for="address">Billing Address<span class="red">*</span></label>
            <input name="address" type="text" class="form-control <?php if(isset($errors["address"])){echo "danger";} ?>" placeholder="Billing Address" value="<?php if (isset($user["address"])) {echo $user["address"];} ?>">
            <?php if(isset($errors["address"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["address"]; ?></small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-6">
            <label for="town">Town / State<span class="red">*</span></label>
            <input type="text" class="form-control <?php if(isset($errors["town"])){echo "danger";} ?>" placeholder="Town" name="town" value="<?php if (isset($user["town"])) {echo $user["town"];} ?>">
            <?php if(isset($errors["town"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["town"]; ?></small>
            <?php endif; ?>
        </div>
        <div class="col-lg-6">
            <label for="phone">Phone Number<span class="red">*</span></label>
            <input type="text" class="form-control <?php if(isset($errors["phone"])){echo "danger";} ?>" placeholder="Phone Number" name="phone" value="<?php if (isset($user["phone"])) {echo $user["phone"];} ?>">
            <?php if(isset($errors["phone"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["phone"]; ?></small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <label for="password">Password<span class="red">*</span></label>
            <input type="password" class="form-control <?php if(isset($errors["password"]) || isset($errors["passwordmatch"])){echo "danger";} ?>" placeholder="Password" name="password" value="">
            <small class="form-text text-muted">Must be Minimum 8 characters at least 1 Alphabet, 1 Number and 1 Special Character</small>
            <?php if(isset($errors["password"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["password"]; ?></small>
            <?php endif; ?>
            <?php if(isset($errors["passwordmatch"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["passwordmatch"]; ?></small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <label for="confirmpass">Confirm Password<span class="red">*</span></label>
            <input type="password" class="form-control <?php if(isset($errors["passwordmatch"])){echo "danger";} ?>" placeholder="Confirm Password" name="confirmpass" value="">
            <?php if(isset($errors["passwordmatch"])): ?>
                <small class="form-control-feedback text-muted red"><?php echo $errors["passwordmatch"]; ?></small>
            <?php endif; ?>
        </div>
    </div>
	<div class="form-group row text-center">
		<div class="col-lg-12">
			<div class="g-recaptcha" style="display:inline-block" data-sitekey="<?php echo GOOGLE_CAPTCHA_SITE_KEY; ?>"></div>
		</div>
		<?php if(isset($errors["captcha"])): ?>
			<small class="form-control-feedback text-muted red"><?php echo $errors["captcha"]; ?></small>
		<?php endif; ?>
	</div>

    <div class="form-group row">
        <div class="col-lg-12">
            <input type="submit" class="form-control btn btn-primary" name="register" value="Register">
        </div>
    </div>
</form>
</div>
<?php elseif(isset($isRegistered) && $isRegistered): ?>
<div class="confirm-email-container">
	<div class="message">
		<h2>Registration Successful!</h2>
		<h5>Thank you for registering with world of reports. We've sent a confirmation message your way.</h5>
		<h5>Check your inbox and spam folders for the confirmation link. </h5>
	</div>
</div>
<?php endif; ?>
<?php require_once "includes/footer.php"; ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$( function() {
    	var availableTags = <?php echo $countryArray; ?>;
    	$( "#tags" ).autocomplete({
			source: availableTags
		});
	});
</script>
