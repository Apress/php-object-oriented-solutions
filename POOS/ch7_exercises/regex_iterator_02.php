<?php
$xml = simplexml_load_file('inventory.xml', 'SimpleXMLIterator');
foreach ($xml as $book) {
	echo 'Entering outer loop<br />';
	$match = new RegexIterator($book->title, '/PHP/');
	foreach ($match as $title) {
		echo 'Entering inner loop<br />';
		echo $title . '<br />';
		echo $book->description . '<br />';
		echo 'Exiting inner loop<br />';
	}
	echo 'Exiting outer loop<br /><br />';
}					
?>