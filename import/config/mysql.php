<?php 
	function connect($dbname) {
		include 'env.php';

		$conn = mysqli_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $dbname)
			or die('Cannot connect to database');
		return $conn;
	}
?>