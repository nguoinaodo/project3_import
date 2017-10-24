<?php 
	function connect() {
		include 'env.php';

		$conn = mysql_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD)
			or die('Cannot connect to database');
		return $conn;
	}

	function use_db($conn, $dbname) {
		mysql_select_db($dbname, $conn)
			or die('Cannot connect to database '.$dbname);
	}
?>