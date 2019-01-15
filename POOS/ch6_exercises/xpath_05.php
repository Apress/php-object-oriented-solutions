<?php
$xml = simplexml_load_file('inventory_ns.xml');
$xml->registerXPathNamespace('ch6', 'http://foundationphp.com/ch6/');
$titles = $xml->xpath('//ch6:title');
echo '<ul>';
foreach ($titles as $title) {
	echo "<li>$title</li>";
}
echo '</ul>';
?>