<?php
$xml = simplexml_load_file('inventory.xml');
foreach ($xml->book as $book) {
	echo $book->title . '<br />';
}
?>
