<?php

require_once("Connection.php");

class Country extends Connection
{

    private $conn;

	function __construct() {
		$this -> conn = $this -> getConnection();
	}

    public function getContinentCountries($continent_id) {
        $query = "SELECT name FROM wor_countries WHERE continent_id = :continent_id";
        try {
			$stmt = $this -> conn-> prepare($query);
            $stmt -> execute(["continent_id" => $continent_id]);
			$row = $stmt->fetchAll();
			return $row;
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
    }

    public function getCountryId($name) {
        $query = "SELECT country_id FROM wor_countries WHERE name = :name";
        try {
			$stmt = $this -> conn-> prepare($query);
            $stmt -> execute(["name" => $name]);
			$row = $stmt->fetch();
			return $row;
		} catch (PDOException $e) {
			$error = new ErrorMaster();
			$error -> reportError($e);
		}
    }
}


?>
