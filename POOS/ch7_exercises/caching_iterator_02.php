<?php
$books = simplexml_load_file('inventory.xml', 'SimpleXMLIterator');
$moreBooks = simplexml_load_file('more_books.xml', 'SimpleXMLIterator');
$limit1 = new LimitIterator($books, 0, 2);
$limit2 = new LimitIterator($moreBooks, 0, 2);
$combined = new AppendIterator();
$combined->append($limit1);
$combined->append($limit2);
echo '<ol>';
foreach ($combined as $book) {
	echo "<li>$book->title";
	$moreThan3 = false;
	if (count($book->author) > 3) {
		$limit3 = new LimitIterator($book->author, 0, 3);
		$authors = new CachingIterator($limit3);
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