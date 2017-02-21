<?php

include "email_template.php";

$to = 'kianojulius@gmail.com';
$subject = "Beautiful HTML Email using PHP by CodexWorld";
// Get HTML contents from file
$link = "http://google.com";

$htmlContent = generateHtml($link);

// Set content-type for sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
$headers .= 'From: jgkiano@gmail.com' . "\r\n";

// Send email
if(mail($to,$subject,$htmlContent,$headers)):
	$successMsg = 'Email has sent successfully.';
else:
	$errorMsg = 'Some problem occurred, please try again.';
endif;
?>
