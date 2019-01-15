<?php
$xml = simplexml_load_file('inventory.xml');
header('Content-Type: text/xml');
echo $xml->book[1]->asXML();
?>