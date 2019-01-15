<?php
$xml = simplexml_load_file('inventory3.xml');
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
	for ($i = 0; $i < $num_authors; $i++) {
		echo $book->author[$i];
		// If there's only one author, break out of the loop
		if ($num_authors == 1) {
			break;
		
		} elseif ($i < ($num_authors - 2)) {
			// If there are more than one authors left, use a comma
			echo ', ';
		} elseif ($i == ($num_authors - 2)) {
			// Otherwise insert an ampersand
			echo ' &amp; ';
		}
	}
	echo '</p>';
								
	echo '<p class="publisher">' . $book->publisher . '</p>';
	// Get the namespaced attributes
	$attributes = $book->attributes('pos', true);
	// For PHP 5.0 and 5.1, replace the preceding line with the following one
	// $attributes = $book->attributes('http://foundationphp.com/ns/pos/');
	echo '<p class="publisher">ISBN: ' . $attributes['isbn13'] . '</p>';
								  
	echo '<p>' . $book->description . '</p>';
}
?>
</body>
</html>
