<?php require_once "includes/header.php"; ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<?php require_once("classes/Reports.php"); ?>

<?php require_once("classes/Country.php"); ?>

<?php

    if(!$auth -> isLoggedIn() && count($auth -> get("user_id")) <= 0) {
        header(BASE_URL_REDIRECT);
    } else {

        $user = $auth -> getUserDetails($auth -> get("user_id"));

        $country = new Country();

        $countries = $country -> getAllCountries();

        $countryArray = array();
        //
        foreach ($countries as $countryName) {
        	array_push($countryArray, $countryName["name"]);
        }
        //
        $countryArray = json_encode($countryArray);

        if(isset($_POST["update"])) {
            $info = array();
            $info["firstname"] = $_POST["firstname"];
            $info["lastname"] = $_POST["lastname"];
            $info["country"] = $_POST["country"];
            $info["zip"] = $_POST["zip"];
            $info["address"] = $_POST["address"];
            $info["phone"] = $_POST["phone"];
            $info["town"] = $_POST["town"];

            $success = $updateStatus = $auth -> updatePersonalInfo($info, $auth -> get("user_id"));

            $errors = $auth -> getErrors();

    		if(array_key_exists("update-info",$errors)) {
    			$errors = $errors["update-info"];
    		}
        }

    }

?>



<div class="content-header-industry">
	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>
</div>

<div class="container profile-container">
    <h4>Your Profile</h4>
    <hr>
    <div class="row">
        <div class="col-sm-4 col-md-3 sidebar">
            <div class="list-group">
                <span href="#" class="list-group-item">
                    Dashboard
                </span>
                <a href="#" class="list-group-item"><i class="fa fa-book fa-fw"></i> Reports</a>
                <a href="#" class="list-group-item active">
                    <i class="fa fa-pencil fa-fw"></i> Edit your personal info
                </a>
                <a href="#" class="list-group-item">
                    <i class="fa fa-envelope-o fa-fw"></i> Change email address
                </a>
                <a href="#" class="list-group-item">
                    <i class="fa fa-key fa-fw"></i> Change password <span class="badge">14</span>
                </a>
            </div>
        </div>
        <div class="col-sm-8 col-md-9 profile-right">
            <h6>Persional Infomation</h6>
            <?php if(!empty($user)): ?>
                <form class="register-form" action="info.php" method="post">
                    <?php if(isset($errors) && !empty($errors)): ?>
                        <p class="error">Hmm, seems like you have a couple of errors</p>
                    <?php endif; ?>
                    <?php if(isset($success) && $success): ?>
                        <p class="success-msg">update successful</p>
                    <?php endif; ?>
                    <hr>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="firstname">First Name<span class="red">*</span></label>
                            <input name= "firstname" type="text"class="form-control <?php if(isset($errors["firstname"])){echo "danger";} ?>" placeholder="First Name" value="<?php if (isset($user["user_firstname"])) {echo $user["user_firstname"];} ?>">
                            <?php if(isset($errors["firstname"])): ?>
                                <small class="form-control-feedback text-muted red"><?php echo $errors["firstname"]; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <label for="lastname">Last Name<span class="red">*</span></label>
                            <input name="lastname" type="text"class="form-control <?php if(isset($errors["lastname"])){echo "danger";} ?>" placeholder="Last Name" value="<?php if (isset($user["user_lastname"])) {echo $user["user_lastname"];} ?>">
                            <?php if(isset($errors["lastname"])): ?>
                                <small class="form-control-feedback text-muted red"><?php echo $errors["lastname"]; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="country">Country<span class="red">*</span></label>
                            <input name= "country" id="tags" type="text"class="form-control <?php if(isset($errors["country"])){echo "danger";} ?>" placeholder="Country" value="<?php if (isset($user["user_billing_country_id"])) {echo $country -> getCountryName($user["user_billing_country_id"]);} ?>">
                            <?php if(isset($errors["country"])): ?>
                                <small class="form-control-feedback text-muted red"><?php echo $errors["country"]; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <label for="zip">ZIP / Postal Code<span class="red">*</span></label>
                            <input name="zip" type="text"class="form-control <?php if(isset($errors["zip"])){echo "danger";} ?>" placeholder="ZIP / Postal Code" value="<?php if (isset($user["user_billing_zip"])) {echo $user["user_billing_zip"];} ?>">
                            <?php if(isset($errors["zip"])): ?>
                                <small class="form-control-feedback text-muted red"><?php echo $errors["zip"]; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label for="address">Billing Address<span class="red">*</span></label>
                            <input name="address" type="text" class="form-control <?php if(isset($errors["address"])){echo "danger";} ?>" placeholder="Billing Address" value="<?php if (isset($user["user_billing_address"])) {echo $user["user_billing_address"];} ?>">
                            <?php if(isset($errors["address"])): ?>
                                <small class="form-control-feedback text-muted red"><?php echo $errors["address"]; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="town">Town / State<span class="red">*</span></label>
                            <input type="text" class="form-control <?php if(isset($errors["town"])){echo "danger";} ?>" placeholder="Town" name="town" value="<?php if (isset($user["user_billing_town"])) {echo $user["user_billing_town"];} ?>">
                            <?php if(isset($errors["town"])): ?>
                                <small class="form-control-feedback text-muted red"><?php echo $errors["town"]; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <label for="phone">Phone Number<span class="red">*</span></label>
                            <input type="text" class="form-control <?php if(isset($errors["phone"])){echo "danger";} ?>" placeholder="Phone Number" name="phone" value="<?php if (isset($user["user_phone"])) {echo $user["user_phone"];} ?>">
                            <?php if(isset($errors["phone"])): ?>
                                <small class="form-control-feedback text-muted red"><?php echo $errors["phone"]; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <input type="submit" class="form-control btn btn-primary" name="update" value="Save">
                        </div>
                    </div>
                </form>
            <?php endif;?>
        </div>
    </div>
</div>






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
