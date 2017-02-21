<?php require_once "includes/header.php"; ?>

<?php require_once("classes/Reports.php"); ?>

<?php
require_once('classes/OAuth.php');

require_once('classes/PesaPalCheckStatus.php');

require_once('classes/Payments.php');



$pesapalMerchantReference	= null;
$pesapalTrackingId 		= null;
$checkStatus 				= new PesaPalCheckStatus();

if(isset($_GET['pesapal_merchant_reference']))
    $pesapalMerchantReference = $_GET['pesapal_merchant_reference'];

if(isset($_GET['pesapal_transaction_tracking_id']))
    $pesapalTrackingId = $_GET['pesapal_transaction_tracking_id'];

/** check status of the transaction made
    *There are 3 available API
    *checkStatusUsingTrackingIdandMerchantRef() - returns Status only.
    *checkStatusByMerchantRef() - returns status only.
    *getMoreDetails() - returns status, payment method, merchant reference and pesapal tracking id
**/

//$status 			= $checkStatus->checkStatusByMerchantRef($pesapalMerchantReference);
$responseArray	= $checkStatus->getTransactionDetails($pesapalMerchantReference,$pesapalTrackingId);
$status 			= $checkStatus->checkStatusUsingTrackingIdandMerchantRef($pesapalMerchantReference,$pesapalTrackingId);

//something fishy is going on
if(!$auth -> isLoggedIn()
    || !isset($_GET['pesapal_merchant_reference'])
    || !isset($_GET['pesapal_transaction_tracking_id'])
    || $responseArray["payment_method"] == NULL
    || $responseArray["pesapal_merchant_reference"] == NULL
    ) {
		header(BASE_URL_REDIRECT);
        die();
	}
//
    $report = new Reports();
    $reportInfo =  $report -> getReport($auth -> get("buy-report"));
    $report = null;
//
    //just for testing will implement ipn later
    var_dump($responseArray);
    if(!empty($responseArray)) {
        $payment = new Payments();
        $updateOrder = $payment -> updateOrder($responseArray);
        if($updateOrder) {
            $auth -> destroy("buy-report");
            $auth -> destroy("StoredReportId");
            $auth -> destroy("ref");
            echo "db updated";
        }
    }

    // echo $pesapalMerchantReference;
    // echo $pesapalTrackingId;
    // echo $status;
    // $responseArray["payment_method"];
    // create report object
?>

<div class="content-header-industry">
	<?php include "includes/desktop-nav.php" ?>
	<?php include "includes/mobile-nav.php" ?>
</div>

<div class="container payment-container extra-height">
    <h5>Payment Confirmation</h5>
    <hr>
    <div class="row">
        <div class="col-lg-6">
            <div class="redirect-message">
                <img src="assets/images/received.png" alt="">
                <h5>PAYMENT RECEIVED</h5>
                <p>Thank you for your purchase, we've sent an email confirmation your way. Hope you enjoy your report</p>
            </div>
        </div>
        <div class="col-lg-6">
            <h6>Report information</h6>
            <hr class="blue-hr">
            <h5><?php echo $reportInfo["report_title"]; ?></h5>
            <p><?php echo $reportInfo["report_overview"]; ?></p>
            <h6 class="report-author">By <?php echo $reportInfo["report_author"]; ?></h6>
            <h4 class="report-price">KES: <?php echo number_format($reportInfo["report_price"]); ?></h4>
            <h6 class="promo"><i class="fa fa-check" aria-hidden="true"></i> Use however you want</h6>
            <h6 class="promo"><i class="fa fa-check" aria-hidden="true"></i> Yours forever</h6>
            <h6 class="promo"><i class="fa fa-check" aria-hidden="true"></i> Support guaranteed</h6>
        </div>
    </div>
</div>



<?php require_once "includes/footer.php"; ?>
