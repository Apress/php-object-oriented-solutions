<?php
$xml = simplexml_load_file('inventory.xml', 'SimpleXMLIterator');
foreach ($xml as $book) {
	$match = new RegexIterator($book->title, '/PHP/');
	foreach ($match as $title) {
		echo $title . '<br />';
		echo $book->description . '<br /><br />';
	}
}					
?>