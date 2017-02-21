<?php require_once "includes/header.php"; ?>

<?php require_once("classes/Reports.php"); ?>


<?php

    if(!$auth -> isLoggedIn() && count($auth -> get("user_id")) <= 0) {
        header(BASE_URL_REDIRECT);
    }

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



<div class="content-header-industry">
	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>
</div>

<div class="container profile-container">
    <h4>Your Profile</h4>
    <hr>
    <div class="row">
        <div class="col-sm-4 col-md-3 sidebar">
            <div class="list-group">
                <span href="#" class="list-group-item">
                    Dashboard
                </span>
                <a href="#" class="list-group-item active"><i class="fa fa-book fa-fw"></i> Reports</a>
                <a href="#" class="list-group-item">
                    <i class="fa fa-pencil fa-fw"></i> Edit your personal info
                </a>
                <a href="#" class="list-group-item">
                    <i class="fa fa-envelope-o fa-fw"></i> Change email address
                </a>
                <a href="#" class="list-group-item">
                    <i class="fa fa-key fa-fw"></i> Change password <span class="badge">14</span>
                </a>
            </div>
        </div>
        <div class="col-sm-8 col-md-9 profile-right">
            <h6>Reports</h6>
			<?php if($totalTransactionReports > 0): ?>
			<table class="table container">
			    <thead>
			        <tr>
			            <th>Report</th>
			            <th>Action</th>
			        </tr>
			    </thead>
			    <tbody>
			        <?php foreach ($userReports as $userReport): ?>
			            <tr>
			                <td><a href="<?php echo BASE_URL . "report.php?r_id=" . $reports -> getReport($userReport["transaction_report_id"])["report_id"];?>">
			                    <?php echo $reports -> getReport($userReport["transaction_report_id"])["report_title"]; ?></a>
			                </td>
			                <td><a href="#" class="btn btn-primary btn-download">Download <i class="fa fa-download" aria-hidden="true"></i></a></td>
			            </tr>
			        <?php endforeach; ?>
			    </tbody>
			</table>
			<?php else: ?>
			    <p>please buy something..please</p>
			<?php endif; ?>
        </div>
    </div>
</div>






<?php require_once "includes/footer.php"; ?>
