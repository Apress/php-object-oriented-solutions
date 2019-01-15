<?php
$xml = simplexml_load_file('inventory.xml');
$output = "<?xml version='1.0' encoding='utf-8'?>\n";
$output .= $xml->book[1]->asXML();
if (file_put_contents( 'book2_dec.xml', $output)) {
	echo 'XML saved';
} else {
	echo 'Could not save XML';
}

?>