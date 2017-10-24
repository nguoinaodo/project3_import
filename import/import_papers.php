
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
		$conn = connect();
		
		// Read raw database
		use_db($conn, $raw_db);
		$sql = "SELECT * FROM paper LIMIT $offset, $MAX_INT";
		// $sql = "SELECT * FROM paper OFFSET $offset";
		$result = mysql_query($sql) or die(mysql_error());
		
		// Handle papers
		use_db($conn, $main_db);
		while ($row_paper = mysql_fetch_assoc($result)) {
			// Paper info
			$id = $row_paper['id'];
			$title = $row_paper['title'];
			$cover_date = $row_paper['coverDate'];
			$abstract = mysql_real_escape_string($row_paper['abstract']);
			$url = mysql_real_escape_string($row_paper['url']);
			$issn = $row_paper['issn'];
			$keywords = preg_split('/,\s*/', trim($row_paper['keywords']), -1, PREG_SPLIT_NO_EMPTY);
			// Insert papers
			insert_papers($id, $title, $cover_date, $abstract, $url, $issn);
			// Insert keywords, links
			handle_keywords($id, $keywords);
		}
		
		// Free and close connection
		mysql_free_result($result);
		mysql_close($conn);
	}
?> 
<?php
	// Insert papers
	function insert_papers($id, $title, $cover_date, $abstract, $url, $issn) {
			$sql = "INSERT INTO papers (id, title, cover_date, abstract, url, issn) VALUES ('$id', '$title', '$cover_date', '$abstract', '$url', '$issn')";
			mysql_query($sql);
	}

	// Insert keywords, links 
	function handle_keywords($paper_id, $keywords) {
		foreach ($keywords as $key => $keyword) {
			// Check exists
			$sql = 'SELECT * FROM keywords WHERE content="$keyword"';
			$r = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_assoc($r);
			if ($row) {
				// If exists
				$keyword_id = $row['id'];
			} else {
				// If not exists, insert
				$sql = "INSERT INTO keywords (content) VALUES ('$keyword')";
				mysql_query($sql);
				$keyword_id = mysql_insert_id();
			}
			// Free result
			mysql_free_result($r); 	
			// Insert links paper-keyword
			$sql = "INSERT INTO keyword_paper (keyword_id, paper_id) VALUES ('$keyword_id', '$paper_id')";
			mysql_query($sql);
		}	
	}
?>