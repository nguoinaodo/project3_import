<?php 
	include 'import/import_authors.php';
	include 'import/import_papers.php';
	include 'import/import_author_paper.php';
	include 'db.php';
	// Import papers
	$start = intval($argv[1]);
	$limit = intval($argv[2]);
	import_papers($raw_db, $start, $limit, $main_db);
?>