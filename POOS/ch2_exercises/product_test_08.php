<?php
require '../Ch2/Book_03.php';

$book = new Ch2_Book('Book', 'PHP Object-Oriented Solutions', 300);
echo '<p>"' . $book->getTitle() . '" has ' . $book->getPageCount() . ' pages</p>';
?>