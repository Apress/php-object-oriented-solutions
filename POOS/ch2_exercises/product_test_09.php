<?php
require '../Ch2/Book_04.php';

$book = new Ch2_Book('PHP Object-Oriented Solutions', 300);
echo '<p>The ' . $book->getProductType() . ' "' . $book->getTitle() . '" has ' . $book->getPageCount() . ' pages</p>';
?>