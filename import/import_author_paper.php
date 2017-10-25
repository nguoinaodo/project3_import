
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
		$conn = connect($raw_db);
		// Read author-paper
		$sql = "SELECT * FROM author_paper LIMIT $offset, $MAX_INT";
		$result = $conn -> query($sql);
		// Insert links
		$conn -> select_db($main_db);
		while ($row_link = $result -> fetch_assoc()) {
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
		$result -> free();
		$conn -> close();
	}
?>
<?php 
	// Check paper exists
	function check_paper_exists($conn, $paper_id) {
		$sql = 'SELECT * FROM papers WHERE id="$paper_id"';
		$r = $conn -> query($sql);
		if (!$r) {
			printf("Error: %s\n", mysqli_error($conn));
			return false;
		}
		$row = $r -> fetch_assoc();
		$r -> free();
		return $row ? true : false;
	}

	// Check author exists
	function check_author_exitst($conn, $author_id) {
		$sql = 'SELECT * FROM authors WHERE id="$author_id"';
		$r = $conn -> query($sql);
		if (!$r) {
			printf("Error: %s\n", mysqli_error($conn));
			return false;
		}
		$row = $r -> fetch_assoc();
		$r -> free();
		return $row ? true : false;
	}

	// Insert link
	function insert_link($conn, $author_id, $paper_id) {
		$sql = 'INSERT INTO author_paper (author_id, paper_id) VALUES ("$author_id", "$paper_id")';
		$conn -> query($sql) or print(mysqli_error($conn));
	}
?>
