
<?php 
	function import_papers($raw_db, $offset, $limit, $main_db) {
		/*
			Import papers info from raw database to project database:

			$raw_db: raw database
			$offset: start position in raw database
			$main_db: project database
		*/
		include 'config/constants.php';
		include_once 'config/mysql.php';
		// Database connection
		$conn = connect($raw_db);
		
		// Read raw database
		$sql = "SELECT * FROM paper LIMIT $offset, $MAX_INT";
		// $sql = "SELECT * FROM paper OFFSET $offset";
		$result = mysqli_query($conn, $sql) or die(mysqli_error());
		mysqli_close($conn);
		// Handle papers
		$conn = connect($main_db) or die(mysqli_error());
		while ($row_paper = mysqli_fetch_assoc($result)) {
			// Paper info
			$id = $row_paper['id'];
			$title = $row_paper['title'];
			$cover_date = $row_paper['coverDate'];
			$abstract = mysqli_real_escape_string($row_paper['abstract']);
			$url = mysqli_real_escape_string($row_paper['url']);
			$issn = $row_paper['issn'];
			$keywords = preg_split('/,\s*/', trim($row_paper['keywords']), -1, PREG_SPLIT_NO_EMPTY);
			// Insert papers
			insert_papers($conn, $id, $title, $cover_date, $abstract, $url, $issn);
			// Insert keywords, links
			handle_keywords($conn, $id, $keywords);
		}
		
		// Free and close connection
		mysqli_free_result($result);
		mysqli_close($conn);
	}
?> 
<?php
	// Insert papers
	function insert_papers($conn, $id, $title, $cover_date, $abstract, $url, $issn) {
			$sql = "INSERT INTO papers (id, title, cover_date, abstract, url, issn) VALUES ('$id', '$title', '$cover_date', '$abstract', '$url', '$issn')";
			mysqli_query($conn, $sql);
	}

	// Insert keywords, links 
	function handle_keywords($conn, $paper_id, $keywords) {
		foreach ($keywords as $key => $keyword) {
			// Check exists
			$sql = 'SELECT * FROM keywords WHERE content="$keyword"';
			$r = mysqli_query($conn, $sql) or die(mysqli_error());
			$row = mysqli_fetch_assoc($r);
			if ($row) {
				// If exists
				$keyword_id = $row['id'];
			} else {
				// If not exists, insert
				$sql = "INSERT INTO keywords (content) VALUES ('$keyword')";
				mysqli_query($conn, $sql);
				$keyword_id = mysqli_insert_id();
			}
			// Free result
			mysqli_free_result($r); 	
			// Insert links paper-keyword
			$sql = "INSERT INTO keyword_paper (keyword_id, paper_id) VALUES ('$keyword_id', '$paper_id')";
			mysqli_query($conn, $sql);
		}	
	}
?>