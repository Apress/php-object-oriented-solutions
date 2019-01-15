<?php
$xml = simplexml_load_file('inventory2.xml');
foreach ($xml->book as $book) {
	// Remove the dollar sign
	$reduced = substr($book->price->paperback, 1);
	// Multiply by .9 to give 10% discount
	$reduced *= .9;
	// Format the number, and reassign it to the original property
	$book->price->paperback = '$' . number_format($reduced, 2);
}
header ('Content-Type: text/xml');
echo $xml->asXML();
?>