<?php
require_once("Constants.php");

class Connection
{
    private static $server = SERVER;

    private static $db = DB;

    private static $user = USER;

    private static $pass = PASSWORD;

    private static $charset = "utf8";

    private static $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    private static function generateDsn() {
    	return "mysql:host=".self::$server.";dbname=".self::$db.";charset=".self::$charset."";
    }

    protected function getConnection() {
  		return new PDO(self::generateDsn(), self::$user, self::$pass, self::$opt);
    }

}
?>
