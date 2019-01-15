<?php
$xml = simplexml_load_file('inventory.xml');
header('Content-Type: text/xml');
echo $xml->asXML();
?>