<?php
$xml = simplexml_load_file('inventory2.xml');
foreach ($xml->book as $book) {
	$distributor = $book->addChild('distributor');
	$distributor->addChild('company', 'Springer-Verlag New York, Inc.');
	$distributor->addChild('location', 'New York, NY');
	$distributor->addChild('country', 'USA');
}
header ('Content-Type: text/xml');
echo $xml->asXML();
?>