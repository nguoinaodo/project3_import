<?php 
	include 'import/import_authors.php';
	include 'import/import_papers.php';
	include 'import/import_author_paper.php';
	// DB name
	$raw_db = 'project3';
	$main_db = 'coauthor_net';
	// Import authors
	import_authors($raw_db, 16537, 70000, $main_db);
	// Import papers
	// import_papers($raw_db, 0, $main_db);
	// Import author-paper
	// import_author_paper($raw_db, 0, $main_db);
?>