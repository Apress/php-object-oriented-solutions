<?php
$xml = simplexml_load_file('inventory2.xml');
foreach ($xml->book as $book) {
	unset($book['isbn13']);
	unset($book->publisher);
	unset($book->price);
	unset($book->description);
}
header ('Content-Type: text/xml');
echo $xml->asXML();
?>