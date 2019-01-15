<?php
$combined = new AppendIterator();
$combined->append(new LimitIterator(simplexml_load_file('inventory.xml', 'SimpleXMLIterator'), 0, 2));
$combined->append(new LimitIterator(simplexml_load_file('more_books.xml', 'SimpleXMLIterator'), 0, 2));
echo '<ol>';
foreach ($combined as $book) {
	echo "<li>$book->title";
	$moreThan3 = false;
	if (count($book->author) > 3) {
		$authors = new CachingIterator(new LimitIterator($book->author, 0, 3));
		$moreThan3 = true;
	} else {
		$authors = new CachingIterator($book->author);
	}
	echo '<ul><li>';
	foreach ($authors as $name) {
		echo $name;
		if ($authors->hasNext()) {
			echo ', ';
		}
	}
	if ($moreThan3) {
		echo ' et al';
	}
	echo '</li></ul></li>';
}
echo '</ol>';
?>