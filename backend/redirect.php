<?php require_once("header.php"); ?>

<?php
require_once('classes/OAuth.php');

require_once('classes/PesaPalCheckStatus.php');

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

    //At this point, you can update your database.
    //In my case i will let the IPN do this for me since it will run
    //IPN runs when there is a status change  and since this is a new transaction,
    //the status has changed for UNKNOWN to PENDING/COMPLETED/FAILED
?>
<h3>Callback/ return URl</h3>
<div class="row-fluid">
	<div class="span6">
        <b>PAYMENT RECEIVED</b>
        <blockquote>
            <b>Merchant reference: </b> <?php echo $pesapalMerchantReference; ?><br />
         	<b>Pesapal Tracking ID: </b> <?php echo $pesapalTrackingId; ?><br />
         	<b>Status: </b> <?php echo $status; ?><br />
			<b>Payment Method: </b> <?php echo $responseArray["payment_method"]; ?><br />
        </blockquote>
    </div>
	<div class="span6">
    	<ol>
        	<li>This is your callback URL. We return merchant reference and pesapal tracking ID only</li>
            <li>Currently we don't have an API to query payment Amount. Always store your data before redirecting to PesaPal's APIs</li>
            <li>Configure your IPN: [domain]/pesapalPHPExample/ipn.php
            	<ul>
                	<li>The IPN link when executed is appended with; notification type, merchant reference and tracking id</li>
                </ul>
            </li>
            <li>You have two options:
            	<ul>
                    <li>Update db on callback.</li>
                    <!-- <li>use IPN to do bd update. </li>
                    Best approach, always use IPN to do all db update or other functions that are executed after a certain Payment status is confirmed.
                </ul> -->
             </li>
            <li>IPN runs when there is a status change. Even a new payment that completes automatically(eg using pesapal wallet) will still call the IPN since there is a ststus change from "UNKNOWN/NEW" to PENDING/COMPLETED/FAILED</li>
            <li>INVALID Status- Returned if:
            	<ul>
                	<li>Your merchant reference is dublicated on the merchant's account and you are using querybymerchantref API</li>
                    <li>The transaction doesnt exist on PesaPal</li>
                </ul>
            </li>
        </ol>
    </div>
</div>
