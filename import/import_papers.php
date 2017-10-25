
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
		$sql = "SELECT * FROM paper LIMIT $offset, $limit";
		$result = $conn -> query($sql) or die(mysqli_error($conn));
		// Handle papers
		$conn -> select_db($main_db);
		while ($row_paper = $result -> fetch_assoc()) {
			// Paper info
			$id = $row_paper['id'];
			$title = $row_paper['title'];
			$cover_date = $conn -> real_escape_string($row_paper['coverDate']);
			$abstract = $conn -> real_escape_string($row_paper['abstract']);
			$url = $conn -> real_escape_string($row_paper['url']);
			$issn = $row_paper['issn'];
			$keywords = preg_split('/,\s*/', trim($row_paper['keywords']), -1, PREG_SPLIT_NO_EMPTY);
			// Insert papers
			insert_papers($conn, $id, $title, $cover_date, $abstract, $url, $issn);
			// Insert keywords, links
			handle_keywords($conn, $id, $keywords);
		}
		
		// Free and close connection
		$result -> free();
		$conn -> close();
	}
?> 
<?php
	// Insert papers
	function insert_papers($conn, $id, $title, $cover_date, $abstract, $url, $issn) {
			$sql = "INSERT INTO papers (id, title, cover_date, abstract, url, issn) VALUES ('$id', '$title', '$cover_date', '$abstract', '$url', '$issn')";
			$conn -> query($sql) or
				printf("Error: %s\n", mysqli_error($conn));
	}

	// Insert keywords, links 
	function handle_keywords($conn, $paper_id, $keywords) {
		foreach ($keywords as $key => $keyword) {
			// Check exists
			$keyword = $conn -> real_escape_string($keyword);
			$sql = 'SELECT * FROM keywords WHERE content="$keyword"';
			$r = mysqli_query($conn, $sql);
			if (!$r) {
				printf("Error: %s\n", mysqli_error($conn));
				continue;
			} 
			$row = $r -> fetch_assoc();
			if ($row) {
				// If exists
				$keyword_id = $row['id'];
			} else {
				// If not exists, insert
				$sql = "INSERT INTO keywords (content) VALUES ('$keyword')";
				if ($conn -> query($sql)) {
					$keyword_id = $conn -> insert_id;
				} else {
					printf("Error: %s\n", mysqli_error($conn));
					continue;
				}
			}
			// Free result
			$r -> free(); 	
			// Insert links paper-keyword
			$sql = "INSERT INTO keyword_paper (keyword_id, paper_id) VALUES ('$keyword_id', '$paper_id')";
			$conn -> query($sql) or 
				printf("Error: %s\n", mysqli_error($conn));
		}	
	}
?>