<div id="desktop-nav">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-2">
				<img src="assets/images/logo_light.png" class="img-responsive" style="width: 100%" alt="">
			</div>
			<div class="col-lg-10">
				<ul>
					<?php foreach ($industriesArray as $industry): ?>
						<li id="<?php echo $industry["industry_id"]; ?>" class="dropdown-link"><a href="industries.php?ind=<?php echo $industry["industry_id"];?>"><?php echo $industry["industry_name"]; ?></a></li>
					<?php endforeach; ?>
					<?php if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])):?>
						<li id="sign-in-link"><a href="#" class="btn btn-primary dropdown-link">Profile</a></li>
						<li id="sign-in-link"><a href="#" class="btn btn-primary dropdown-link">Logout</a></li>
        			<?php else: ?>
			           <li id="sign-in-link"><a href="#" class="btn btn-primary dropdown-link">Sign In</a></li>
					<li id="sign-in-link"><a href="#" class="btn btn-primary dropdown-link">Register</a></li>
        			<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="dropdown">
</div>
