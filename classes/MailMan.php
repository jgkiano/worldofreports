<?php

require_once("Constants.php");

require('email-templates/confirm_registration_template.php');

class MailMan extends Connection {

    public function sendConfirmationEmail($email,$key) {
        $subject = "Welcome To World Of Reports";
        // Get HTML contents from file
        $link = BASE_URL."confirm.php?k=".$key;

        $htmlContent = generateHtml($link);

        // Set content-type for sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // Additional headers
        $headers .= 'From: jgkiano@gmail.com' . "\r\n";

        // Send email
        if(mail($email,$subject,$htmlContent,$headers)):
        	return true;
        else:
        	return false;
        endif;
    }

}


?>
