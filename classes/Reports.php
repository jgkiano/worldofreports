<?php

require_once("Connection.php");

class Reports extends Connection
{
	private $conn;

	public $totalRecords;

	public $limit = 10;

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
		$start_from = ($page-1) * 10;
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

	public function getUserTransactionReports($userId, $page) {
		$start_from = ($page-1) * 10;

		$query = "SELECT * FROM wor_transactions WHERE transaction_user_id = :transaction_user_id AND status = :status ORDER BY transaction_date DESC LIMIT :start_from, :limit";

		try {
			$stmt = $this -> conn ->prepare($query);
			$stmt->execute([
				"transaction_user_id" => $userId,
				"start_from" => $start_from,
				"limit" => 10,
				"status" => "completed"
			]);
			return $stmt->fetchAll();
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getAllUserTransactionReports($userId) {
		$query = "SELECT * FROM wor_transactions WHERE transaction_user_id = :transaction_user_id";

		try {
			$stmt = $this -> conn ->prepare($query);
			$stmt->execute([
				"transaction_user_id" => $userId,
			]);
			return $stmt->rowCount();
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getSectorFeatured($sector_id = null) {
		if($sector_id == null) {
			$query = "SELECT * FROM wor_reports ORDER BY report_uploaded_date DESC";
		} else {
			$query = "SELECT * FROM wor_reports WHERE report_sector_id = :report_sector_id ORDER BY report_uploaded_date DESC";
		}

		try {
			if($sector_id == null) {
				$stmt = $this-> conn ->query($query);
			} else {
				$stmt = $this -> conn ->prepare($query);
				$stmt->execute(["report_sector_id" => $sector_id]);
			}
			return $stmt->fetch();
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}

	}

	public function getIndustryInfoFromSectorId($sectorId) {
		$query = "SELECT industry_id FROM wor_sectors WHERE sector_id = :sector_id";
		try {
			$stmt = $this -> conn ->prepare($query);
			$stmt->execute(["sector_id" => $sectorId]);
			$industry_id = $stmt -> fetch()["industry_id"];
			$query = "SELECT * FROM wor_industries WHERE industry_id = :industry_id";
			$stmt = $this -> conn ->prepare($query);
			$stmt->execute(["industry_id" => $industry_id]);
			return $stmt -> fetch();
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getSectorInfo($sectorId) {
		$query = "SELECT * FROM wor_sectors WHERE sector_id = :sector_id";
		try {
			$stmt = $this -> conn ->prepare($query);
			$stmt->execute(["sector_id" => $sectorId]);
			return $stmt -> fetch();
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function reportExists($report_id) {
		$query = "SELECT * FROM wor_reports WHERE report_id = :report_id";
		try {
			$stmt = $this -> conn ->prepare($query);
			$stmt->execute(["report_id" => $report_id]);
			if($stmt -> rowCount() == 1) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}


}

?>
