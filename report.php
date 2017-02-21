<?php
require_once("classes/Reports.php");

require_once("classes/Search.php");

require_once("classes/Payments.php");

require_once("classes/Country.php");

//here we only need report id

if (isset($_GET["r_id"]) && is_numeric($_GET["r_id"])) {

    $reportId = $_GET["r_id"];

    // create report object
    $report = new Reports();

    // create search object for recommendations
    $search = new Search();

    $reportInfo =  $report -> getReport($reportId);

	$sector = $reportInfo["report_sector_id"];

    $country = new Country();


}


?>

<?php require_once "includes/header.php"; ?>
<div class="content-header-industry">

	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>

	<div class="content-title">
		<h3><?php echo $reportInfo["report_title"]; ?></h3> <em><a href="http://localhost/worldofreports/">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
			<?php echo $report -> getIndustryInfoFromSectorId($reportInfo["report_sector_id"])["industry_name"]; ?> <i class="fa fa-angle-right" aria-hidden="true"></i>
		<?php echo $report -> getSectorInfo($reportInfo["report_sector_id"])["sector_name"]; ?>  <i class="fa fa-angle-right" aria-hidden="true"></i> <?php echo $reportInfo["report_title"]; ?></em>
		<h5><?php echo $reportInfo["report_overview"]; ?></h5>
	</div>


</div>

<div class="content-body container-fluid">
	<div class="row">
		<div class="col-lg-2">
            <div class="socials">
                <h6>Share this report</h6>
                <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
            </div>
		</div>
		<div class="col-lg-8 report-info-container">
            <div class="report-details">
                <h5><?php echo $country -> getCountryName($reportInfo["country_id"]);?></h5>
    			<h6><?php echo date("M jS, Y", strtotime($reportInfo["report_published_date"])); ?></h6>
    			<h4 class="report-price">KES: <?php echo number_format($reportInfo["report_price"]); ?></h4>
            </div>
            <form class="" action="precheckout.php" method="post">
                <input type="hidden" name="report_id" value="<?php echo $reportInfo["report_id"]; ?>">
                <input type="submit" name="reportcheckout" class="btn btn-primary result-button buy-button" value="Buy Report">
            </form>
            <div class="report-about">
                <h2>About this report</h2>
                <span style="display:block;">By: <?php echo $reportInfo["report_author"];?></span>
    			<?php echo $reportInfo["report_description"];?>
            </div>
		</div>
		<div class="col-lg-2 suggestions-side">
			<?php require_once("right-suggestions.php") ?>
		</div>
	</div>
</div>
<?php require_once "includes/footer.php"; ?>
