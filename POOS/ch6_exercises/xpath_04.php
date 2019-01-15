<?php
###################################################
# This script displays a blank screen because the #
# XML document has declared a default namespace.  #
###################################################
$xml = simplexml_load_file('inventory_ns.xml');
$titles = $xml->xpath('//title');
echo '<ul>';
foreach ($titles as $title) {
	echo "<li>$title</li>";
}
echo '</ul>';
?>