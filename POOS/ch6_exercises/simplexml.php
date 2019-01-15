<?php
$xml = simplexml_load_file('inventory.xml');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Using SimpleXML</title>
<link href="simplexml.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Brush Up Your Programming Skills</h1>
<?php
foreach ($xml->book as $book) {
	echo '<h2>' . $book->title . '</h2>';
	
	$num_authors = count($book->author);
	echo '<p class="author">';
	foreach ($book->author as $author) {
		echo $author;
	}
	/*for ($i = 0; $i < $num_authors; $i++) {
		echo $book->author[$i];
		if ($num_authors == 1) {
			break;
		} elseif ($i < ($num_authors - 2)) {
			echo ', ';
		} elseif ($i == ($num_authors - 2)) {
			echo ' &amp; ';
		}
	}*/
	echo '</p>';
								
	echo '<p class="publisher">' . $book->publisher . '</p>';
	echo '<p class="publisher">ISBN: ' . $book['isbn13'] . '</p>';
								  
	echo '<p>' . $book->description . '</p>';
}
?>
</body>
</html>
