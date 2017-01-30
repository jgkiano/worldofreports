<?php require_once("header.php") ?>

<?php require_once("../classes/Reports.php") ?>

<?php

    if(isset($_GET["page"]) && is_numeric($_GET["page"])) {
    	$page = ceil($_GET["page"]);
    } else {
    	$page = 1;
    }

    $user = $auth -> getUserDetails($auth -> get("user_id"));

    $reports = new Reports();

    $totalTransactionReports = $reports -> getAllUserTransactionReports($auth -> get("user_id"));

    if($totalTransactionReports > 0) {
        $userReports = $reports -> getUserTransactionReports($auth -> get("user_id"), $page);
    }
?>




<h1>Welcome, <?php echo $user["user_firstname"] . " " . $user["user_lastname"]; ?></h1>

<a href="info.php">Edit your information</a>
<a href="emailchange.php">Change email address</a>
<a href="pchange.php">Change password</a>
<?php if($totalTransactionReports > 0): ?>
<table class="table container">
    <thead>
        <tr>
            <th>Report</th>
            <th>Transaction Reference</th>
            <th>Status</th>
            <th>Date</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($userReports as $userReport): ?>
            <tr>
                <td><a href="<?php echo BASE_URL . "report.php?r_id=" . $reports -> getReport($userReport["transaction_report_id"])["report_id"];?>">
                    <?php echo $reports -> getReport($userReport["transaction_report_id"])["report_title"]; ?></a>
                </td>
                <td><?php echo $userReport["transaction_reference"]; ?></td>
                <td><?php echo $userReport["status"]; ?></td>
                <td><?php echo $userReport["transaction_date"]; ?></td>
                <td><a href="#" class="btn btn-default">Download</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>please buy something..please</p>
<?php endif; ?>


<div align="center">
<ul class='pagination text-center' id="pagination">

<?php if(!empty($totalTransactionReports)):for($i=1; $i<=$totalTransactionReports; $i++): if($i == 1): ?>

	<li <?php if($page == $i) : ?> class="active" <?php endif; ?>

	id="<?php echo $i; ?>">

	<a href='?page=<?php echo $i; ?>'><?php echo $i; ?></a></li>

<?php else: ?>
	<li <?php if($page == $i) : ?> class="active" <?php endif; ?>

	id="<?php echo $i; ?>">

	<a href='?page=<?php echo $i; ?>'><?php echo $i; ?></a></li>

</li>
<?php endif; ?>
<?php endfor; endif; ?>
</div>


<?php require_once("footer.php") ?>
