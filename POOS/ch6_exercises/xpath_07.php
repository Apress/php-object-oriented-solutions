<?php
$xml = simplexml_load_file('foed.xml');
$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
$dates = $xml->xpath('//dc:date');
echo '<ul>';
foreach ($dates as $date) {
	echo "<li>$date</li>";
}
echo '</ul>';
?>