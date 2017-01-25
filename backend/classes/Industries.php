<?php
require_once("Connection.php");

require_once("ErrorMaster.php");

class Industry extends Connection
{
	private $conn;

	function __construct() {
		$this -> conn = $this -> getConnection();
	}

	public function getAllIndustries() {
		try {
			$stmt = $this -> conn-> query('SELECT * FROM wor_industries');
			$row = $stmt->fetchAll();
			return $row;
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getAllSectors() {
		try {
			$stmt = $this -> conn-> query('SELECT * FROM wor_sectors');
			$row = $stmt->fetchAll();
			return $row;
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

}

?>
