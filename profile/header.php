<?php

require_once($_SERVER['DOCUMENT_ROOT'] ."/worldofreports/classes/Authentication.php");

require_once("classes/Industries.php");

Authentication::startSession();

$auth = new Authentication();

$industry = new Industry();

$industriesArray =  $industry -> getAllIndustries();

$industry = null;

?>
<!DOCTYPE html>
<html>
<head>
	<title>World of records</title>
	<!-- bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<!-- font awesome -->
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,900|Open+Sans:300,400,700" rel="stylesheet">
	<!-- custom css -->
	<link rel="stylesheet" type="text/css" href="http://localhost/worldofreports/assets/css/style.css">
</head>
<body>
