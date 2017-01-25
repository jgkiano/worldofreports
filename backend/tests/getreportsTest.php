<?php
//requiring simple test
require_once('../simpletest/autorun.php');

//requireing guestbook.php - this can be any file you have that contains classes
require_once('../classes/reports.php');

//the class we're using to test out code - inherits things from UnitTestCase
class TestGetReports extends UnitTestCase {

	// test the viewing of the guestbook with entries 
    function testGetReportsArray()
    {
        $report = new Reports();

        $reports = $report -> getReports();

        $this -> assertTrue($reports, array());
        
    }

}

?>