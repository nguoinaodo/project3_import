<?php 
	function connect($dbname) {
		include 'env.php';

		$conn = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $dbname)
			or die('Cannot connect to database');
		return $conn;
	}
?>