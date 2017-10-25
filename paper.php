<?php 
	include 'import/import_authors.php';
	include 'import/import_papers.php';
	include 'import/import_author_paper.php';
	include 'db.php';
	// Import papers
	import_papers($raw_db, 0, 0, $main_db);
?>