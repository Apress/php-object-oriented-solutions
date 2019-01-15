<?php
$xml = simplexml_load_file('inventory.xml', 'SimpleXMLIterator');
$limiter = new LimitIterator($xml, 2, 3);
foreach ($limiter as $book) {
	echo $book->title . '<br />';
}					
?>