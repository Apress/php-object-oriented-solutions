<?php
$xml = simplexml_load_file('inventory_ns.xml');
$xml->registerXPathNamespace('ch6', 'http://foundationphp.com/ch6/');
$books = $xml->xpath('//ch6:book');
echo '<ul>';
foreach ($books as $book) {
	$isbn = $book->attributes();
	echo "<li>$isbn</li>";
}
echo '</ul>';
?>