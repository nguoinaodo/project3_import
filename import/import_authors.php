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
		$result = $conn -> query($sql) or die(mysqli_error());
		// Import
		$conn -> select_db($main_db);
		while ($row_author = $result -> fetch_assoc()) {
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
			if (!$country_id) {
				continue;
			}
			// City
			$city_id = handle_city($conn, $city, $country_id, $UNKNOWN);
			if (!$city_id) {
				continue;
			}
			// University
			$university_id = handle_university($conn, $university, $city_id, $UNKNOWN);
			if (!$university_id) {
				continue;
			}
			// Insert author
			$id = $row_author['id'];
			$surname = $row_author['surname'];
			$given_name = $row_author['givenName'];
			$email = $conn -> real_escape_string($row_author['email']);
			$url = $conn -> real_escape_string($row_author['url']);
			insert_authors($conn, $id, $surname, $given_name, $email, $url, $university_id);
			
			// Insert subjects and links
			$subjects = preg_split('/,\s*/', strtolower($row_author['subjects']), -1, PREG_SPLIT_NO_EMPTY);
			handle_subjects($conn, $id, $subjects);
		}

		// Free results
		$result -> free();
		// Close connection
		$conn -> close();
	}
?>
<?php
	// Insert authors
	function insert_authors($conn, $id, $surname, $given_name, $email, $url, $university_id) {
			$sql = "INSERT INTO authors (id, given_name, surname, email, url, university_id) VALUES ('$id', '$surname', '$given_name', '$email', '$url', '$university_id')";
			if ($conn -> query($sql)) {
				printf("Error %s\n", $conn -> sqlstate);
			};
	}

	// Country 
	function handle_country($conn, $country, $UNKNOWN) {
		preg_match('/^\s*$/', $country, $matches);
		if (count($matches) > 0) {
			$country = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM countries WHERE name='$country'";
		$r = $conn -> query($sql);
		if ($r) {
			$row = $r -> fetch_assoc();
			if ($row) {
				$country_id = $row['id'];
			} else {
				// Insert if not exists
				$sql = "INSERT INTO countries (name) VALUES ('$country')";
				if ($conn -> query($sql)) {
					$country_id = $conn -> insert_id();
				} else {
					$country_id = null;
				}
			}
			$r -> free();
			return $country_id;
		} else {
			printf("Error: %s\n", $conn -> sqlstate);
			$r = $conn -> query("SELECT * FROM countries WHERE name='$UNKNOWN'");
			if ($r) {
				$row = $r -> fetch_assoc();
				$r -> free();
				if ($row) {
					$country_id = $row['id'];
				} else {
					$country_id = null;
				}
			} else {
				printf("Error: %s\n", $conn -> sqlstate);
				$country_id = null;
			}
			return $country_id;
		}
	}

	// City
	function handle_city($conn, $city, $country_id, $UNKNOWN) {
		preg_match('/^\s*$/', $city, $matches);
		if (count($matches) > 0) {
			$city = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM cities WHERE name='$city' AND country_id='$country_id'";
		$r = $conn -> query($sql);
		if ($r) {
			$row = $r -> fetch_assoc();
			if ($row) {
				$city_id = $row['id'];
			} else {
				// Insert if not exists
				$sql = "INSERT INTO cities (name, country_id) VALUES ('$city', '$country_id')";
				if ($conn -> query($sql)) {
					$city_id = $conn -> insert_id(); 
				} else {
					printf("Error: %s\n", $conn -> sqlstate);
					$city_id = null;
				}
			}
			$r -> free();
			return $city_id;
		} else {
			printf("Error: %s\n", $conn -> sqlstate);
			$r = $conn -> query("SELECT * FROM cities WHERE name='$UNKNOWN'");
			if ($r) {
				$row = $r -> fetch_assoc();
				if ($row) {
					$city_id = $row['id'];
				} else {
					$city_id = null;
				}
				$r -> free();
			} else {
				printf("Error: %s\n", $conn -> sqlstate);
				$city_id = null;
			}
			return $city_id;
		}
	}

	// University
	function handle_university($conn, $university, $city_id, $UNKNOWN) {
		preg_match('/^\s*$/', $university, $matches);
		if (count($matches) > 0) {
			$university = $UNKNOWN;
		}
		// Check exists
		$sql = "SELECT * FROM universities WHERE name='$university' AND city_id='$city_id'";
		$r = $conn -> query($sql);
		if ($r) {
			$row = $r -> fetch_assoc();
			if ($row) {
				$university_id = $row['id'];
			} else {
				// Insert if not exists
				$sql = "INSERT INTO universities (name, city_id) VALUES ('$university', '$city_id')";
				if ($conn -> query($sql)) {
					$university_id = $conn -> insert_id();
				} else {
					printf("Error: %s\n", $conn -> sqlstate);
					$university_id = null;
				}
			}
			$r -> free();
			return $university_id;
		} else {
			printf("Error: %s\n", $conn -> sqlstate);
			$r = $conn -> query("SELECT * FROM universities WHERE name='$UNKNOWN'");
			if ($r) {
				$row = $r -> fetch_assoc();
				if (!$row) {
					$university_id = null;
				} else {
					$university_id = $row['id'];
				}
				$r -> free();
			} else {
				printf("Error: %s\n", $conn -> sqlstate);
				$university_id = null;
			}
			return $university_id;
		}
		
	}

	function handle_subjects($conn, $author_id, $subjects) {
		foreach ($subjects as $key => $subject) {
			preg_match('/^\s*$/', $subject, $matches);
			if (count($matches) > 0) {
				continue;
			}
			// Check subject exists
			$sql = "SELECT * FROM subjects WHERE name='$subject'";
			$r = $conn -> query($sql);
			if (!$r) {
				printf("Error: %s\n", $conn -> sqlstate);
				continue;
			}
			$row = $r -> fetch_assoc();
			if ($row) {
				$subject_id = $row['id'];
			} else {
				// Insert if not exists
				$sql = "INSERT INTO subjects (name) VALUES ('$subject')";
				if ($conn -> query($sql)) {
					$subject_id = $conn -> insert_id();
				} else {
					printf("Error: %s\n", $conn -> sqlstate);
					continue;
				}
			}
			$r -> free();
			// Insert author-subject
			$sql = "INSERT INTO author_subject (author_id, subject_id) VALUES ('$author_id', '$subject_id')";
			$conn -> query($sql) or 
				printf("Error: %s\n", $conn -> sqlstate);
		}
	}
?>