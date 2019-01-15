<?php
$xml = simplexml_load_file('inventory2.xml');
foreach ($xml->book as $book) {
	if (strpos($book->title, 'PHP') !== false) {
		$book->addAttribute('category', 'PHP');
	} else {
		$book->addAttribute('category', 'Web design');
	}
}
header ('Content-Type: text/xml');
echo $xml->asXML();
?>