<?php
require_once("classes/Search.php");
if(isset($_GET["q"])) {
	$search = new Search();
    $q = strtolower($_GET["q"]);
    if(isset($_GET["page"]) && is_numeric($_GET["page"])) {
        $page = ceil($_GET["page"]);
    } else {
        $page = 1;
    }
    $results = $search-> getReportsPage($page,$q);
    $total_pages = $search -> getTotalPages($q);
}
?>

<?php require_once("header.php"); ?>

<?php foreach ($results as $result) : ?>
    <li><a href="report.php?r_id=<?php echo $result["report_id"] ?>"><?php echo $result["report_title"];?> </a>
		<ul>
			<li>Country: <?php echo $result["country_id"];?></li>
			<li><?php echo $result["report_author"];?></li>
			<li>Published On:<?php echo $result["report_published_date"];?></li>
			<li>Uploaded On:<?php echo $result["report_uploaded_date"];?></li>
			<li>Buy: <?php echo $result["report_price"];?></li>
			<li><?php echo $result["report_overview"];?></li>
		</ul>
	</li>
<?php endforeach ?>

<div align="center">
	<ul class='pagination text-center' id="pagination">

    <?php if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++): if($i == 1):?>
    	<li <?php if($page == $i) : ?> class="active" <?php endif; ?>
    	id="<?php echo $i;?>">
    	<a href='search.php?q=<?php echo $q . "&"; ?> page=<?php echo $i; ?>'><?php echo $i;?></a></li>
    <?php else:?>
    	<li <?php if($page == $i) : ?> class="active" <?php endif; ?>
	    	id="<?php echo $i;?>">
	    	<a href='search.php?q=<?php echo $q . "&"; ?>page=<?php echo $i; ?>'><?php echo $i;?></a>
    	</li>
    <?php endif;?>
    <?php endfor;endif;?>
</div>

<?php include("footer.php"); ?>
