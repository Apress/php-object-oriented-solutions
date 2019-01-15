<pre>
<?php
$files = array('inventory.xml', 'createXML.php', 'modify_xml_03.php');
$iterator = new ArrayIterator($files);
$regex = new RegexIterator($iterator, '/(\w+?)e/', RegexIterator::ALL_MATCHES);
print_r(iterator_to_array($regex));
?>
</pre>