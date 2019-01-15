<?php
$xml = simplexml_load_file('inventory.xml');
if ($xml->book[1]->asXML('book2.xml')) {
	echo 'XML saved';
} else {
	echo 'Could not save XML';
}

?>