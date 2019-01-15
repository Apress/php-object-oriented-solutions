<?php
$xml = simplexml_load_file('inventory.xml');
$titles = $xml->xpath('//title');
echo '<ul>';
foreach ($titles as $title) {
	echo "<li>$title</li>";
}
echo '</ul>';
?>