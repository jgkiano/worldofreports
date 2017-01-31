<?php
require_once("classes/Reports.php");

require_once("classes/Search.php");

require_once("classes/Payments.php");

//here we only need report id

if (isset($_GET["r_id"]) && is_numeric($_GET["r_id"])) {

    $reportId = $_GET["r_id"];

    // create report object
    $report = new Reports();

    // create search object for recommendations
    $search = new Search();

    $reportInfo =  $report -> getReport($reportId);

    $report = null;
}


?>
<?php require_once("header.php"); ?>

<?php
if($auth -> isLoggedIn()) {
    $payment = new Payments();
    $ownsThis = $payment -> ownsThis($reportInfo["report_id"], $auth -> get("user_id"));
}
?>

<?php if(isset($reportInfo)): ?>
    <?php $auth -> set("report_id", $reportInfo["report_id"]);?>
    <li>Title: <?php echo $reportInfo["report_title"];?>
		<ul>
			<li>Country: <?php echo $reportInfo["country_id"];?></li>
			<li><?php echo $reportInfo["report_author"];?></li>
			<li>Published On:<?php echo $reportInfo["report_published_date"];?></li>
			<li>Uploaded On:<?php echo $reportInfo["report_uploaded_date"];?></li>
			<li>Buy: <?php echo $reportInfo["report_price"];?></li>
			<li>Overview: <?php echo $reportInfo["report_overview"];?></li>
            <li>Description: <?php echo $reportInfo["report_description"];?></li>
		</ul>
	</li>

    <?php if($auth -> isLoggedIn()): ?>
        <?php if($ownsThis): ?>
            <a href="#" class="btn btn-default">Download</a>
            <h6>You have purchased this item</h6>
        <?php else: ?>
            <form action="checkout.php" method="post">
                <input type="hidden" name="r_id" value="<?php echo $reportInfo["report_id"];?>">
                <input type="submit" value="Buy Report" class="btn btn-default" />
            </form>
        <?php endif; ?>
    <?php else: ?>
        <a href="register.php" class="btn btn-default">Buy Report</a>
    <?php endif; ?>

<?php else: ?>
    <p>No results found</p>
<?php endif; ?>

<?php
if(isset($reportInfo) && count($reportInfo) > 0) {
    $reccos = $search -> getSpecificReccomendations(
        $reportInfo["country_id"],
        $reportInfo["report_sector_id"],
        $reportInfo["report_id"]
    );
}
?>

<?php
if(count($reccos) > 0): ?>
    <p>These might interest you: </p>
    <ul>
        <?php foreach ($reccos as $recco): ?>
        <a href="report.php?r_id=<?php echo $recco['report_id']; ?>"><li><?php echo $recco["report_title"]; ?></li></a>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>



<?php require_once("footer.php"); ?>
