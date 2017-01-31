<?php
require_once("Connection.php");

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

	public function getAllSectors($industry_id) {
		try {
			$stmt = $this -> conn-> prepare('SELECT sector_id, sector_name FROM wor_sectors WHERE industry_id = :industry_id');
			$stmt -> execute(["industry_id" => $industry_id]);
			$row = $stmt->fetchAll();
			return $row;
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getSectorInfo($sectorId) {
		try {
			$stmt = $this -> conn-> prepare('SELECT * FROM wor_sectors WHERE sector_id = :sector_id');
			$stmt -> execute(["sector_id" => $sectorId]);
			$row = $stmt->fetch();
			return $row;
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getIndustryInfo($industry_id) {
		try {
			$stmt = $this -> conn-> prepare('SELECT * FROM wor_industries WHERE industry_id = :industry_id');
			$stmt -> execute(["industry_id" => $industry_id]);
			$row = $stmt->fetch();
			return $row;
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}
	
}

?>
