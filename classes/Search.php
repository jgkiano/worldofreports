<?php


require_once("Authentication.php");

class Search extends Connection
{
	private $conn;

    private $totalSearchResults;

    private $searchLimit = 20;

	private $auth;

    function __construct() {
		$this -> conn = $this -> getConnection();
		$this -> auth = new Authentication();
	}

    public function getReportsPage($page, $q) {
        $start_from = ($page-1) * $this -> searchLimit;
        $query = "SELECT * FROM wor_reports WHERE MATCH(report_title,report_author,report_overview) AGAINST(:q) LIMIT :start_from, :max";
		try {
			$stmt = $this -> conn -> prepare($query);
	        $stmt->execute([
	        'start_from' => $start_from,
	        'max' => $this -> searchLimit,
	        'q' => $q
	        ]);
	        return $stmt->fetchAll();
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
    }

	private function getRandomReports($limit = 5) {
		$query = "SELECT * FROM wor_reports ORDER BY RAND() LIMIT :limit";
		try {
			$stmt = $this -> conn -> prepare($query);
			$stmt->execute(['limit' => $limit]);
			return $stmt->fetchAll();
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getGeneralReccomendations($sectorId = null, $limit = 5) {
		if($sectorId == null) {
			$query = "SELECT * FROM wor_reports WHERE country_id = :country_id ORDER BY RAND() LIMIT :limit";
		} else {
			$query = "SELECT * FROM wor_reports WHERE report_sector_id = :report_sector_id AND country_id = :country_id ORDER BY RAND() LIMIT :limit";
		}
		try {
			$stmt = $this -> conn -> prepare($query);
			if($sectorId == null) {
				$stmt->execute([
					'country_id' => $this -> auth -> getUserCountryId(),
					'limit' => $limit
				]);
				$reports = $stmt -> fetchAll();
				if(count($reports) < 5) {
					return array_merge($reports, $this -> getRandomReports($limit - count($reports)));
				} else {
					return $reports;
				}
			}else {
				$stmt->execute([
					'report_sector_id' => $sectorId,
					'country_id' => $this -> auth -> getUserCountryId(),
					'limit' => $limit
				]);
				$reports = $stmt -> fetchAll();
				if(count($reports) < 5) {
					$reports = $reports + $this -> getGeneralReccomendations(null, $limit - count($reports));
					if(count($reports) < 5 ) {
						return array_merge($reports, $this -> getRandomReports($limit - count($reports)));
					} else {
						return $reports;
					}
				} else {
					return $reports;
				}
			}
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

	public function getSpecificReccomendations($country, $sector, $reportId) {
		$query = "SELECT * FROM wor_reports WHERE  report_sector_id = :report_sector_id AND country_id = :country_id AND report_id NOT IN (:report_id)LIMIT 5";
		try {
			$stmt = $this -> conn -> prepare($query);
			$stmt->execute([
							'report_sector_id' => $sector,
							'country_id' => $country,
							'report_id' => $reportId
						]);
			if($stmt -> rowCount() > 0) {
				return $stmt->fetchAll();
			} else {
				$query = "SELECT * FROM wor_reports WHERE country_id = :country_id AND report_id NOT IN (:report_id) ORDER BY RAND() LIMIT 5";
				$stmt = $this -> conn -> prepare($query);
				$stmt->execute([
								'country_id' => $country,
								'report_id' => $reportId
							]);
				return $stmt->fetchAll();
			}
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
	}

    public function getTotalPages($q) {
        $query = "SELECT * FROM wor_reports WHERE MATCH(report_title,report_author,report_overview) AGAINST(:q)";
		try {
			$stmt = $this -> conn -> prepare($query);
	        $stmt->execute([ 'q' => $q ]);
	        $this -> totalRecords = $stmt->rowCount();
	        return ceil($this -> totalRecords  / $this -> searchLimit);
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
    }
}

?>
