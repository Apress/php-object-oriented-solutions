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
	$authors = new CachingIterator($book->author);
	echo '<ul><li>';
	foreach ($authors as $name) {
		echo $name;
		if ($authors->hasNext()) {
			echo ', ';
		}
	}
	echo '</li></ul></li>';
}
echo '</ol>';
?>