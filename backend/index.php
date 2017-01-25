<?php
require_once("classes/Industries.php");
$industry = new Industry();
$industriesArray =  $industry -> getAllIndustries();
$sectorsArray =  $industry -> getAllSectors();
$industry = null;
?>

<?php require_once("header.php"); ?>

<form method="get" action="search.php">
    <input type="text" name="q" placeholder="search for report, author, statitics">
    <button type="submit">Search</button>
</form>

<ul>
    <?php if(!isset($industriesArray["error"]) && !isset($sectorsArray["error"])) {?>
        <!-- this can be expanded to make sure that reports actually exist for that sector and industry by looping through report'sector id -->
        <?php foreach ($industriesArray as $industry) { ?>
            <li><?php echo $industry["industry_name"]; ?>
                <ul>
                    <?php foreach ($sectorsArray as $sector) { if ($sector["industry_id"] == $industry["industry_id"]) { ?>
                        <li><a href="reports.php?sector=<?php echo $sector["sector_id"] ?>"><?php echo $sector["sector_name"];?></a></li>
            		<?php } } ?>
            	</ul>
            </li>
        <?php } ?>
    <?php } else{ ?>
        <?php echo $industriesArray["error"]; ?>
    <?php } ?>
</ul>

<?php include("footer.php"); ?>
