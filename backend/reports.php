<?php
require_once("classes/Reports.php");

require_once("classes/Search.php");

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

if(isset($_GET["country"]) && is_numeric($_GET["country"])) {
	$country = $_GET["country"];
} else {
	$country = null;
}

if(isset($_GET["size"]) && is_numeric($_GET["size"])) {
	$size = $_GET["size"];
} else {
	$size = null;
}

if(isset($_GET["sector"]) && is_numeric($_GET["sector"])) {
	$sector = $_GET["sector"];
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

} else {
	echo "nothing to see here :(";
}

?>
<?php require_once("header.php"); ?>

<?php

//getting last viewed report from report class

if($auth->get("report_id") != null && count($auth->get("report_id")) > 0 && isset($report)) {

	$report_id =$auth->get("report_id");

	$lastReport = $report -> getReport($report_id);

} elseif (isset($report)) {
	// kill report object since we dont need it
	$report = null;
}

//getting recommendation; if user hasnt clicked on a report, we recommend reports based on current sector and users country

//this is just making sure we have reports in this category
if (isset($reportsArray) && count($reportsArray) > 0) {

	$reccos = $search -> getGeneralReccomendations($sector); 
}
?>

<?php if(count($reccos) > 0): ?>

	<p>You might be intterested in these reports</p>

	<ul>
		<?php foreach ($reccos as $recco): ?>
			<a href="report.php?r_id=<?php echo $recco["report_id"]; ?>"><li><?php echo $recco["report_title"]; ?></li></a>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>

<?php if(isset($lastReport) && count($lastReport) > 0): ?>
	<p>You last viewed: <a href="report.php?r_id=<?php echo $lastReport['report_id']?>"><?php echo $lastReport['report_title']?></a></p>
<?php endif; ?>

<?php if(isset($reportsArray) && count($reportsArray) > 0): ?>
	<form action="reports.php" method="get">
		<input type="hidden" name="sector" value="<?php echo $sector; ?>">
		<input type="text" name="country" placeholder="country">
		<select name="order">
			<option value="uploaded">Uploaded</option>
			<option value="published">Published</option>
		</select>
		<input type="submit">
	</form>
<?php foreach ($reportsArray as $report): ?>
	<li><a href="report.php?r_id=<?php echo $report["report_id"] ?>"><?php echo $report["report_title"]; ?> </a>
		<ul>
			<li>Country: <?php echo $report["country_id"]; ?></li>
			<li><?php echo $report["report_author"]; ?></li>
			<li>Published On:<?php echo $report["report_published_date"]; ?></li>
			<li>Uploaded On:<?php echo $report["report_uploaded_date"]; ?></li>
			<li>Buy: <?php echo $report["report_price"]; ?></li>
			<li><?php echo $report["report_overview"]; ?></li>
		</ul>
	</li>
<?php endforeach ?>
<?php else: ?>
	<p>No results found</p>
<?php endif; ?>


<div align="center">
<ul class='pagination text-center' id="pagination">

<?php if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++): if($i == 1): ?>

	<li <?php if($page == $i) : ?> class="active" <?php endif; ?>

	id="<?php echo $i; ?>">

	<a href='reports.php?sector=

	<?php echo $sector . "&"; ?>

	page=<?php echo $i . "&"; ?>

	country=<?php echo $country . "&"; ?>

	order=<?php echo $order; ?>'>

	<?php echo $i; ?></a></li>

<?php else: ?>
	<li <?php if($page == $i) : ?> class="active" <?php endif; ?>

	id="<?php echo $i; ?>">

	<a href='reports.php?

	sector=<?php echo $sector . "&"; ?>

	page=<?php echo $i . "&" ?>

	country=<?php echo $country . "&" ;?>

	order=<?php echo $order; ?>'>

	<?php echo $i; ?></a>

</li>
<?php endif; ?>
<?php endfor; endif; ?>
</div>

<?php require_once("footer.php"); ?>