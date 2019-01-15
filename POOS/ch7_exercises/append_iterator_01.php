<?php
$books = simplexml_load_file('inventory.xml', 'SimpleXMLIterator');
$moreBooks = simplexml_load_file('more_books.xml', 'SimpleXMLIterator');
$combined = new AppendIterator();
$combined->append($books);
$combined->append($moreBooks);
echo '<ol>';
foreach ($combined as $book) {
	echo "<li>$book->title</li>";
}
echo '</ol>';
?>