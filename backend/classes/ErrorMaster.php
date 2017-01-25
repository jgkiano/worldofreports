<?php
require_once("Authentication.php");

require_once("Connection.php");

class ErrorMaster extends Connection
{
    private $conn;

    private $selfErrorCount = 0;

    function __construct() {
		$this -> conn = $this -> getConnection();
	}

    //this is a bad way of getting errors, but we're trying to get the most info about errors as possible INITIALLY!!!

    public function reportError($exception = null) {
        try {
            if(!isset($_SESSION)) {
                $_SESSION["error_master"] = "no session recorded";
            }
            $stmt = $this -> conn-> prepare('INSERT INTO wor_errors (error_date, pdo_exception, server_agent, ip_addr, server_name) VALUES (:error_date, :pdo_exception, :server_agent, :ip_addr, :server_name)');
            $stmt -> execute([
                "error_date" => date("Y-m-d H:i:s"),
                "pdo_exception" => $exception,
                "server_agent" => $_SERVER["HTTP_USER_AGENT"],
                "ip_addr" => $_SERVER["REMOTE_ADDR"],
                "server_name"=> $_SERVER["SERVER_NAME"]
            ]);
            if($stmt -> rowCount() > 0) {
                header(ERROR_PAGE);
                die();
            }
		} catch (PDOException $e) {
            //well this is a fail  :"D, i guess we'll report to ourselves..but make sure we dont do it until the end of time.
            //maybe email the error

            if($this -> selfErrorCount > 0) {
                header(ERROR_PAGE);
                die();
            }  else {
                $this -> selfErrorCount = 1;
                $this -> reportError($e);
            }
		}
    }
}

?>
