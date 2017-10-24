
<?php 
	function import_author_paper($raw_db, $offset, $limit, $main_db) {
		/*
			Import author-paper links from raw database to project database (if both author and paper exists):

			$raw_db: raw database's name
			$offset: position in raw author-paper table to start to select from
			$main_db: project database's name 
		*/
		include 'config/constants.php';
		include_once 'config/mysql.php';
		// Database connection
		$conn = connect();
		
		// Read author-paper
		use_db($conn, $raw_db);
		$sql = "SELECT * FROM `author_paper` LIMIT $offset, $limit";
		$result = mysql_query($sql);

		// Insert links
		use_db($conn, $main_db);
		while ($row_link = mysql_fetch_assoc($result)) {
			$paper_id = $row_link['paperid'];
			$author_id = $row_link['authorid'];
			// Check author exists
			if (!check_paper_exists($paper_id)) {
				continue;
			}
			// Check paper exists
			if (!check_author_exitst($author_id)) {
				continue;
			}
			// Insert link
			insert_link($author_id, $paper_id);
		}

		// Close
		mysql_free_result($result);
		mysql_close($conn);
	}
?>
<?php 
	// Check paper exists
	function check_paper_exists($paper_id) {
		$sql = "SELECT * FROM papers WHERE id='$paper_id'";
		$r = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_assoc($r);
		mysql_free_result($r);
		return $row ? true : false;
	}

	// Check author exists
	function check_author_exitst($author_id) {
		$sql = "SELECT * FROM authors WHERE id='$author_id'";
		$r = mysql_query($sql);
		$row = mysql_fetch_assoc($r);
		mysql_free_result($r);
		return $row ? true : false;
	}

	// Insert link
	function insert_link($author_id, $paper_id) {
		$sql = "INSERT INTO author_paper (author_id, paper_id) VALUES ('$author_id', '$paper_id')";
		mysql_query($sql);
	}
?>
