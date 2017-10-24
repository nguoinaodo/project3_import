<?php 
	include 'import/import_authors.php';
	include 'import/import_papers.php';
	include 'import/import_author_paper.php';
	// DB name
	$raw_db = 'project3_raw';
	$main_db = 'project3';
	// Import authors
	// import_authors($raw_db, 0, 70000, $main_db);
	// Import papers
	// import_papers($raw_db, 0, 10, $main_db);
	// Import author-paper
	import_author_paper($raw_db, 0, 10, $main_db);
?>