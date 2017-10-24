<?php
	
	function import_authors($raw_db, $offset, $limit, $main_db) {
		/*
			Import authors info from raw database to project database:

			$raw_db: raw database
			$offset: start position in raw database
			$main_db: project database
		*/
		include 'config/constants.php';
		include_once 'config/mysql.php';

		// Connect to raw database 
		$conn = connect();

		// Read authors
		use_db($conn, $raw_db);
		$sql = "SELECT * FROM author LIMIT $offset, $MAX_INT";
		// $sql = "SELECT * FROM author OFFSET $offset";
		$result = mysql_query($sql) or die(mysql_error());
		
		// Import
		use_db($conn, $main_db);
		while ($row_author = mysql_fetch_assoc($result)) {
			// Affiliation
			$affiliation = preg_split('/,\s*/', $row_author['affiliation'], -1, PREG_SPLIT_NO_EMPTY);
			$n = count($affiliation);
			$university = $affiliation[0];
			$country = $affiliation[$n - 1];
			$city = $affiliation[$n - 2];
			$abbreviation = [];
			for ($i=1; $i < $n - 2; $i++) { 
				array_push($abbreviation, $affiliation[$i]);
			}
			$abbreviation = join(', ', $abbreviation);
			// Country
			$country_id = handle_country($country, $UNKNOWN);
			// City
			$city_id = handle_city($city, $country_id, $UNKNOWN);
			// University
			$university_id = handle_university($university, $city_id, $UNKNOWN);

			// Insert author
			$id = $row_author['id'];
			$surname = $row_author['surname'];
			$given_name = $row_author['givenName'];
			$email = mysql_real_escape_string($row_author['email']);
			$url = mysql_real_escape_string($row_author['url']);
			insert_authors($id, $surname, $given_name, $email, $url, $university_id);
			
			// Insert subjects and links
			$subjects = preg_split('/,\s*/', strtolower($row_author['subjects']), -1, PREG_SPLIT_NO_EMPTY);
			handle_subjects($id, $subjects);
		}

		// Free results
		mysql_free_result($result);
		// Close connection
		mysql_close($conn);
	}
?>
<?php
	// Insert authors
	function insert_authors($id, $surname, $given_name, $email, $url, $university_id) {
			$sql = "INSERT INTO authors (id, given_name, surname, email, url, university_id) VALUES ('$id', '$surname', '$given_name', '$email', '$url', '$university_id')";
			mysql_query($sql);
	}

	// Country 
	function handle_country($country, $UNKNOWN) {
		preg_match('/^\s*$/', $country, $matches);
		if (count($matches) > 0) {
			$country = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM countries WHERE name='$country'";
		$r = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_assoc($r);
		if ($row) {
			$country_id = $row['id'];
		} else {
			// Insert if not exists
			$sql = "INSERT INTO countries (name) VALUES ('$country')";
			mysql_query($sql);
			$country_id = mysql_insert_id();
		}
		mysql_free_result($r);
		return $country_id;
	}

	// City
	function handle_city($city, $country_id, $UNKNOWN) {
		preg_match('/^\s*$/', $city, $matches);
		if (count($matches) > 0) {
			$city = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM cities WHERE name='$city' AND country_id='$country_id'";
		$r = mysql_query($sql);
		$row = mysql_fetch_assoc($r);
		if ($row) {
			$city_id = $row['id'];
		} else {
			// Insert if not exists
			$sql = "INSERT INTO cities (name, country_id) VALUES ('$city', '$country_id')";
			mysql_query($sql);
			$city_id = mysql_insert_id(); 
		}
		mysql_free_result($r);
		return $city_id;
	}

	// University
	function handle_university($university, $city_id, $UNKNOWN) {
		preg_match('/^\s*$/', $university, $matches);
		if (count($matches) > 0) {
			$university = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM universities WHERE name='$university' AND city_id='$city_id'";
		$r = mysql_query($sql);
		$row = mysql_fetch_assoc($r);
		if ($row) {
			$university_id = $row['id'];
		} else {
			// Insert if not exists
			$sql = "INSERT INTO universities (name, city_id) VALUES ('$university', '$city_id')";
			mysql_query($sql);
			$university_id = mysql_insert_id(); 
		}
		mysql_free_result($r);
		return $university_id;
	}

	function handle_subjects($author_id, $subjects) {
		foreach ($subjects as $key => $subject) {
			preg_match('/^\s*$/', $subject, $matches);
			if (count($matches) > 0) {
				continue;
			}
			// Check subject exists
			$sql = "SELECT * FROM subjects WHERE name='$subject'";
			$r = mysql_query($sql);
			$row = mysql_fetch_assoc($r);
			if ($row) {
				$subject_id = $row['id'];
			} else {
				// Insert if not exists
				$sql = "INSERT INTO subjects (name) VALUES ('$subject')";
				mysql_query($sql);
				$subject_id = mysql_insert_id();
			}
			mysql_free_result($r);
			// Insert author-subject
			$sql = "INSERT INTO author_subject (author_id, subject_id) VALUES ('$author_id', '$subject_id')";
			mysql_query($sql);
		}
	}
?>