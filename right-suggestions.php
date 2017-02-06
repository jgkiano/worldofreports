<?php

require_once("classes/Reports.php");

$report = new Reports();

//getting last viewed report from report class
if($auth->get("report_id") != null && count($auth->get("report_id")) > 0 && isset($report)) {
	$report_id =$auth->get("report_id");
	$lastReport = $report -> getReport($report_id);
}

if(isset($sector)) {
    $featured = $report -> getSectorFeatured($sector);
} else {
    $featured = $report -> getSectorFeatured();
}
if(isset($sector)) {
    $generalReccos = $search -> getGeneralReccomendations($sector);
} else {
    $generalReccos = $search -> getGeneralReccomendations();
}


?>

<?php if(isset($lastReport) && count($lastReport) > 0): ?>
    <p class="suggestion-title">You last viewed: </p>
    <div class="suggestion-item">
        <h6><a href="report.php?r_id=<?php echo $lastReport['report_id']?>"><?php echo $lastReport['report_title']?></a></h6>
        <p class="author">By: <?php echo $lastReport["report_author"]; ?>, <?php echo $lastReport["report_published_date"]; ?></p>
    </div>
<?php endif; ?>

<?php if(!empty($featured)): ?>
<p class="suggestion-title">Featured: </p>
<div class="suggestion-item">
    <h6><a href="report.php?r_id=<?php echo $featured["report_id"]; ?>"><?php echo $featured["report_title"]; ?></a></h6>
    <p class="author">By: <?php echo $featured["report_author"]; ?>, <?php echo $featured["report_published_date"]; ?></p>
</div>
<?php endif; ?>

<?php if(isset($generalReccos) && !empty($generalReccos)): ?>
    <p class="suggestion-title">You might be interested in:</p>
    <?php foreach ($generalReccos as $recco): ?>
        <div class="suggestion-item">
            <h6><a href="report.php?r_id=<?php echo $recco["report_id"]; ?>"><?php echo $recco["report_title"]; ?></a></h6>
            <p class="author">By: <?php echo $recco["report_author"]; ?>, <?php echo date("M Y", strtotime($recco["report_published_date"])); ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
