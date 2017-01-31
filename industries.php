<?php require_once "includes/header.php"; ?>
<?php 

if(isset($_GET["ind"])) {
	$industryId = $_GET["ind"];
	foreach ($industriesArray as $industry) {
		if($industry["industry_id"] == $industryId) {
			$industryInfo = $industry;
		}
	}
	if(empty($industryInfo)) {
		header(BASE_URL_REDIRECT);
	} else {
		$industry = new Industry();
		$sectorsArray = $industry -> getAllSectors($industryId);
		$half = ceil(count($sectorsArray) / 2);
	}
} else {
	header(BASE_URL_REDIRECT);
}

?>
<div class="content-header-industry">

	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>

	<div class="content-title">
		<h1><?php echo $industryInfo["industry_name"] ?></h1>
		<em><a href="http://localhost/worldofreports/">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <?php echo $industryInfo["industry_name"] ?></em>
		<h5><?php echo $industryInfo["industry_description"] ?></h5>
	</div>

</div>

<div class="content-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 category-1">
				<h2>SECTORS UNDER <?php echo $industryInfo["industry_name"] ?></h2>
				<div class="row">
					<?php if($half > 1): ?>
						<div class="col-lg-6">
							<ul>
								<?php for ($i=0; $i <= $half ; $i++): ?>
									<li class="category-list"><a href="reports.php?s=<?php echo $sectorsArray[$i]["sector_id"]; ?>"><?php echo $sectorsArray[$i]["sector_name"]; ?></a></li>
								<?php endfor; ?>
							</ul>
						</div>
						<div class="col-lg-6">
							<ul>
								<?php for ($i = $half+1; $i < count($sectorsArray); $i++): ?>
									<li class="category-list"><a href="reports.php?s=<?php echo $sectorsArray[$i]["sector_id"]; ?>"><?php echo $sectorsArray[$i]["sector_name"]; ?></a></li>
								<?php endfor; ?>
							</ul>
						</div>
					<?php else: ?>
						<div class="col-lg-6">
							<ul>
								<?php for ($i=0; $i < count($sectorsArray); $i++): ?>
									<li class="category-list"><a href="reports.php?s=<?php echo $sectorsArray[$i]["sector_id"]; ?>"><?php echo $sectorsArray[$i]["sector_name"]; ?></a></li>
								<?php endfor; ?>
							</ul>
						</div>
					<?php endif; ?>		
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once "includes/footer.php"; ?>