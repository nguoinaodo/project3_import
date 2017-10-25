<?php 
	include 'import/import_authors.php';
	include 'import/import_papers.php';
	include 'import/import_author_paper.php';
	include 'db.php';
	// Import authors
	import_authors($raw_db, 0, 0, $main_db);
?>