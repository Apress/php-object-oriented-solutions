<?php
$xml = simplexml_load_file('inventory.xml');
$isbn13 = $xml->xpath('/inventory/book/@isbn13');
echo '<ul>';
foreach ($isbn13 as $isbn) {
	echo "<li>$isbn</li>";
}
echo '</ul>';
?>