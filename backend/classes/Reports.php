<?php

require_once("Connection.php");

class Reports extends Connection
{
	private $conn;

	public $totalRecords;

	public $limit = 20;

	function __construct() {
		$this -> conn = $this -> getConnection();
	}

	public function getTotalSectorReports($gets) {
		//we must have a sector_id
		$query = "SELECT * FROM wor_reports WHERE report_sector_id = :sector_id ";
		//if country is set get those results
		if(isset($gets["country"]) && is_numeric($gets["country"])) {
			$query .= "AND country_id = :country ";
		}
		try {
			$stmt = $this -> conn ->prepare($query);
			if(isset($gets["country"]) && is_numeric($gets["country"])) {
				$stmt->execute([
				'sector_id' => $gets["sector"],
				'country' => $gets["country"]
				]);
				$this -> totalRecords = $stmt->rowCount();
			} else {
				$stmt->execute([
				'sector_id' => $gets["sector"],
				]);
				$this -> totalRecords = $stmt->rowCount();
			}
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getPage($page, $gets) {
		$this -> getTotalSectorReports($gets);
		$start_from = ($page-1) * 20;
		//we must have a sector_id
		$query = "SELECT * FROM wor_reports WHERE report_sector_id = :sector_id ";
		//if country is set get those results
		if(isset($gets["country"]) && is_numeric($gets["country"])) {
			$query .= "AND country_id = :country ";
		}
		if(!isset($gets["order"]) || $gets["order"] != "published" || $gets["order"] == null) {
		//order and limit will go with default values
			$query .= "ORDER BY report_uploaded_date DESC LIMIT :start_from, :limit";
		}
		if($gets["order"] == "published") {
			//order and limit will go with default values
			$query .= "ORDER BY report_published_date DESC LIMIT :start_from, :limit";
		}
		try {
			$stmt = $this -> conn ->prepare($query);
			if(isset($gets["country"]) && is_numeric($gets["country"])) {
				$stmt->execute([
				'sector_id' => $gets["sector"],
				'country' => $gets["country"],
				'start_from' => $start_from,
				'limit' => $this -> limit
				]);
			} else {
				$stmt->execute([
				'sector_id' => $gets["sector"],
				'start_from' => $start_from,
				'limit' => $this -> limit
				]);
			}
			return $stmt->fetchAll();
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getReport($reportId) {
		try {
			$stmt = $this -> conn ->prepare('SELECT * FROM wor_reports WHERE report_id = ?');
			$stmt->execute([$reportId]);
			return $stmt->fetch();
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getReportSearch($sector) {
		try {
			$stmt = $this -> conn ->prepare('SELECT * FROM wor_reports WHERE report_sector_id = ?');
			$stmt->execute([$sector]);
			return $stmt->fetchAll();
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getTotalRecords() {
		return ceil($this -> totalRecords  / $this -> limit);
	}
}

?>
