<?php
require_once("classes/Registration.php");

if(isset($_GET["k"])) {
    $key = $_GET["k"];
    $register = new Register();
    $message = $register -> confirmUserEmail($key);
} 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Email confirmation page</title>
    </head>
    <body>
        <?php if(isset($message["msg"])) { echo $message["msg"]; }?>
        <a href="<?php echo BASE_URL; ?>">Home</a>
    </body>
</html>
