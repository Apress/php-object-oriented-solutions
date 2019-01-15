<?php
$xml = simplexml_load_file('inventory.xml');
if ($xml->asXML('inventory_copy.xml')) {
	echo 'XML saved';
} else {
	echo 'Could not save XML';
}
?>