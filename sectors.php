<?php
require_once("includes/header.php");

require_once("classes/Industries.php");

?>
<?php
if(isset($_GET["ind"])) {

	$industryId = $_GET["ind"];

	$industry = new Industry();

	$sectorsArray =  $industry -> getAllSectors($industryId);

	$industry = null;
}
?>

<?php foreach ($sectorsArray as $sector): ?>
	<li class="category-list"><a href="#"><?php echo $sector["sector_name"]; ?></a></li>
<?php endforeach; ?>