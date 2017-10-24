
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
		$conn = connect($raw_db) or die(mysqli_error());
		
		// Read author-paper
		$sql = "SELECT * FROM `author_paper` LIMIT $offset, $MAX_INT";
		$result = mysqli_query($conn, $sql);
		mysqli_close($conn);
		// Insert links
		$conn = connect($main_db) or die(mysqli_error());
		while ($row_link = mysqli_fetch_assoc($result)) {
			$paper_id = $row_link['paperid'];
			$author_id = $row_link['authorid'];
			// Check author exists
			if (!check_paper_exists($conn, $paper_id)) {
				continue;
			}
			// Check paper exists
			if (!check_author_exitst($conn, $author_id)) {
				continue;
			}
			// Insert link
			insert_link($conn, $author_id, $paper_id);
		}

		// Close
		mysqli_free_result($result);
		mysqli_close($conn);
	}
?>
<?php 
	// Check paper exists
	function check_paper_exists($conn, $paper_id) {
		$sql = "SELECT * FROM papers WHERE id='$paper_id'";
		$r = mysqli_query($conn, $sql) or die(mysqli_error());
		$row = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		return $row ? true : false;
	}

	// Check author exists
	function check_author_exitst($conn, $author_id) {
		$sql = "SELECT * FROM authors WHERE id='$author_id'";
		$r = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		return $row ? true : false;
	}

	// Insert link
	function insert_link($conn, $author_id, $paper_id) {
		$sql = "INSERT INTO author_paper (author_id, paper_id) VALUES ('$author_id', '$paper_id')";
		mysqli_query($conn, $sql);
	}
?>
