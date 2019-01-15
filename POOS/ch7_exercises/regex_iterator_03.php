<pre>
<?php
$files = array('inventory.xml', 'createXML.php', 'modify_xml_03.php');
$iterator = new ArrayIterator($files);
$regex = new RegexIterator($iterator, '/(\w+?)e/', RegexIterator::GET_MATCH);
print_r(iterator_to_array($regex));
?>
</pre>