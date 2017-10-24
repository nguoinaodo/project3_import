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
		$conn = connect($raw_db);

		// Read authors
		$sql = "SELECT * FROM author LIMIT $offset, $MAX_INT";
		// $sql = "SELECT * FROM author OFFSET $offset";
		$result = mysqli_query($conn, $sql) or die(mysqli_error());
		mysqli_close($conn);
		// Import
		$conn = connect($main_db);
		while ($row_author = mysqli_fetch_assoc($result)) {
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
			$country_id = handle_country($conn, $country, $UNKNOWN);
			// City
			$city_id = handle_city($conn, $city, $country_id, $UNKNOWN);
			// University
			$university_id = handle_university($conn, $university, $city_id, $UNKNOWN);

			// Insert author
			$id = $row_author['id'];
			$surname = $row_author['surname'];
			$given_name = $row_author['givenName'];
			$email = mysqli_real_escape_string($row_author['email']);
			$url = mysqli_real_escape_string($row_author['url']);
			insert_authors($conn, $id, $surname, $given_name, $email, $url, $university_id);
			
			// Insert subjects and links
			$subjects = preg_split('/,\s*/', strtolower($row_author['subjects']), -1, PREG_SPLIT_NO_EMPTY);
			handle_subjects($conn, $id, $subjects);
		}

		// Free results
		mysqli_free_result($result);
		// Close connection
		mysqli_close($conn);
	}
?>
<?php
	// Insert authors
	function insert_authors($conn, $id, $surname, $given_name, $email, $url, $university_id) {
			$sql = "INSERT INTO authors (id, given_name, surname, email, url, university_id) VALUES ('$id', '$surname', '$given_name', '$email', '$url', '$university_id')";
			mysqli_query($conn, $sql);
	}

	// Country 
	function handle_country($conn, $country, $UNKNOWN) {
		preg_match('/^\s*$/', $country, $matches);
		if (count($matches) > 0) {
			$country = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM countries WHERE name='$country'";
		$r = mysqli_query($conn, $sql) or die(mysqli_error());
		$row = mysqli_fetch_assoc($r);
		if ($row) {
			$country_id = $row['id'];
		} else {
			// Insert if not exists
			$sql = "INSERT INTO countries (name) VALUES ('$country')";
			mysqli_query($conn, $sql);
			$country_id = mysqli_insert_id();
		}
		mysqli_free_result($r);
		return $country_id;
	}

	// City
	function handle_city($conn, $city, $country_id, $UNKNOWN) {
		preg_match('/^\s*$/', $city, $matches);
		if (count($matches) > 0) {
			$city = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM cities WHERE name='$city' AND country_id='$country_id'";
		$r = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($r);
		if ($row) {
			$city_id = $row['id'];
		} else {
			// Insert if not exists
			$sql = "INSERT INTO cities (name, country_id) VALUES ('$city', '$country_id')";
			mysqli_query($sql);
			$city_id = mysqli_insert_id(); 
		}
		mysqli_free_result($r);
		return $city_id;
	}

	// University
	function handle_university($conn, $university, $city_id, $UNKNOWN) {
		preg_match('/^\s*$/', $university, $matches);
		if (count($matches) > 0) {
			$university = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM universities WHERE name='$university' AND city_id='$city_id'";
		$r = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($r);
		if ($row) {
			$university_id = $row['id'];
		} else {
			// Insert if not exists
			$sql = "INSERT INTO universities (name, city_id) VALUES ('$university', '$city_id')";
			mysqli_query($conn, $sql);
			$university_id = mysqli_insert_id(); 
		}
		mysqli_free_result($r);
		return $university_id;
	}

	function handle_subjects($conn, $author_id, $subjects) {
		foreach ($subjects as $key => $subject) {
			preg_match('/^\s*$/', $subject, $matches);
			if (count($matches) > 0) {
				continue;
			}
			// Check subject exists
			$sql = "SELECT * FROM subjects WHERE name='$subject'";
			$r = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($r);
			if ($row) {
				$subject_id = $row['id'];
			} else {
				// Insert if not exists
				$sql = "INSERT INTO subjects (name) VALUES ('$subject')";
				mysqli_query($conn, $sql);
				$subject_id = mysqli_insert_id();
			}
			mysqli_free_result($r);
			// Insert author-subject
			$sql = "INSERT INTO author_subject (author_id, subject_id) VALUES ('$author_id', '$subject_id')";
			mysqli_query($conn, $sql);
		}
	}
?>