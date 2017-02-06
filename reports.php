<?php

require_once "includes/header.php";


require_once("classes/Reports.php");

require_once("classes/Search.php");

require_once("classes/Country.php");

$countryObj = new Country();

$countries = $countryObj -> getContinentCountries("AF");

$countryArray = array();

foreach ($countries as $country) {
	array_push($countryArray, $country["name"]);
}

$countryArray = json_encode($countryArray);


?>


<?php

// create report object
$report = new Reports();

//create new search object for recommendations
$search = new Search();


//all possible GETS
if(isset($_GET["order"]) && $_GET["order"]!="") {
	if($_GET["order"] == "published" || $_GET["order"] == "uploaded")
	$order = $_GET["order"];
} else {
	$order = null;
}

if(isset($_GET["country"])) {
	$name = $_GET["country"];
	$country = $countryObj -> getCountryId($name)["country_id"];
} else {
	$country = null;
}

if(isset($_GET["size"]) && is_numeric($_GET["size"])) {
	$size = $_GET["size"];
} else {
	$size = null;
}

if(isset($_GET["s"]) && is_numeric($_GET["s"])) {
	$sector = $_GET["s"];
} else {
	$sector = null;
}

if(isset($_GET["size"]) && is_numeric($_GET["size"])) {
	$limit = $_GET["size"];
} else {
	$limit = null;
}

if(isset($_GET["page"]) && is_numeric($_GET["page"])) {
	$page = ceil($_GET["page"]);
} else {
	$page = 1;
}

//sector is a must
if(isset($sector) && is_numeric($sector)) {

	$params = array();

	$params["sector"] = $sector;

	$params["order"] = $order;

	$params["country"] = $country;

	$reportsArray =  $report -> getPage($page,$params);

	$total_pages = $report -> getTotalRecords();

	$industry = new Industry();

	$sectorInfo = $industry -> getSectorInfo($sector);

	$industryInfo = $industry -> getIndustryInfo($sectorInfo["industry_id"]);

	if(empty($sectorInfo)) {
		echo "here";
		// header(BASE_URL_REDIRECT);
	}

} else {
	echo "here 2";
	// header(BASE_URL_REDIRECT);
}

?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="content-header-alcoholic">
	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>
	<div class="content-title">
		<h1><?php echo $sectorInfo["sector_name"]; ?></h1>
		<em><a href="http://localhost/worldofreports/">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="http://localhost/worldofreports/industries.php?ind=<?php echo $industryInfo["industry_id"]; ?>"><?php echo $industryInfo["industry_name"]; ?></a> <i class="fa fa-angle-right" aria-hidden="true"></i> <?php echo $sectorInfo["sector_name"]; ?></em>
		<h5><?php echo $sectorInfo["sector_description"]; ?></h5>
	</div>
</div>

<?php if(isset($reportsArray) && count($reportsArray) > 0): ?>
	<div class="content-body-results container-fluid">
		<div class="row">
			<div class="col-lg-2">
				<div class="sort-box">
					<span>Filer results</span>
					<form action="reports.php" class="filter-form" method="get">
						<input type="hidden" name="s" value="<?php echo $sector; ?>">
						<div class="form-group">
							<input type="text" placeholder="Country" name="country" value="<?php if(isset($name)){ echo $name; } ?>" class="form-control" id="tags">
						</div>
						<div class="form-group">
							<select class="form-control" name="order">
								<?php if(!isset($order) || $order == "uploaded"): ?>
									<option selected value="uploaded">Uploaded Date</option>
									<option value="published">Published Date</option>
								<?php else: ?>
									<option value="uploaded">Uploaded Date</option>
									<option selected value="published">Published Date</option>
								<?php endif; ?>
							</select>
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit">Filter <i class="fa fa-refresh" aria-hidden="true"></i></button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="results-container">
					<?php foreach ($reportsArray as $report): ?>
						<div class="result-item">
							<em>Uploaded On: <?php echo date("M jS, Y", strtotime($report["report_uploaded_date"])); ?></em>
							<h4 class="report-title"><a href="report.php?r_id=<?php echo $report["report_id"]; ?>"><?php echo $report["report_title"]; ?></a></h4>
							<p class="report-teaser"><?php echo mb_strimwidth($report["report_overview"], 0, 350, "...");?></p>
							<span>By: <?php echo $report["report_author"]; ?>, <?php echo date("M jS Y", strtotime($report["report_published_date"])); ?></span>
							<h4 class="report-price">KES: <?php echo number_format($report["report_price"]); ?></h4>
							<a href="report.php?r_id=<?php echo $report["report_id"]; ?>" class="btn btn-primary result-button view-button">View Details</a>
							<a class="btn btn-primary result-button buy-button">Buy Report</a>
						</div>
					<?php endforeach ?>
				</div>
			</div>
			<div class="col-lg-2 suggestions-side">
				<?php require_once("right-suggestions.php"); ?>
			</div>
		</div>
	</div>
<?php else: ?>
	<p>No results found</p>
<?php endif; ?>



<div align="center" style="background-color: #F0F0F0">
<ul class='pagination text-center' id="pagination">

<?php if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++): if($i == 1): ?>

	<li <?php if($page == $i) : ?> class="page-item active" <?php endif; ?>

	id="<?php echo $i; ?>">

	<a class="page-link" href='reports.php?s=

	<?php echo $sector . "&"; ?>

	page=<?php echo $i . "&"; ?>

	country=<?php echo $country . "&"; ?>

	order=<?php echo $order; ?>'>

	<?php echo $i; ?></a></li>

<?php else: ?>
	<li <?php if($page == $i) : ?> class="page-item active" <?php endif; ?>

	id="<?php echo $i; ?>">

	<a class="page-link" href='reports.php?

	s=<?php echo $sector . "&"; ?>

	page=<?php echo $i . "&" ?>

	country=<?php echo $country . "&" ;?>

	order=<?php echo $order; ?>'>

	<?php echo $i; ?></a>

</li>
<?php endif; ?>
<?php endfor; endif; ?>


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
