<pre>
<?php
$author = array('name'   => 'David',
			   'city'    => 'London',
			   'country' => 'United Kingdom');
$iterator = new ArrayIterator($author);
$regex = new RegexIterator($iterator, '/^c/', RegexIterator::MATCH, RegexIterator::USE_KEY);
print_r(iterator_to_array($regex));
?>
</pre>