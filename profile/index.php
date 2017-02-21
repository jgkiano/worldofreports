<?php require_once "includes/header.php"; ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/worldofreports/classes/Reports.php"); ?>



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
                    <i class="fa fa-bar-chart-o fa-fw"></i> Transactions <span class="badge">14</span>
                </a>
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
        <div class="col-sm-8 col-md-9 sidebar">
            <h5>Reports</h5>
            <hr class="blue-hr">
            <table class="table table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Username</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td colspan="2">Larry the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>
        </div>
    </div>
</div>





<?php require_once "includes/footer.php"; ?>
