<?php
$xml = simplexml_load_file('inventory.xml');
echo $xml->book[3]->title . ' (ISBN: ' . $xml->book[3]['isbn13'] . ')';
?>
