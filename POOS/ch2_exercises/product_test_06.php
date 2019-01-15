<?php
require '../Ch2/Book_01.php';
require '../Ch2/DVD_01.php';

$product1 = new Ch2_Book('Book', 'PHP Object-Oriented Solutions');
$product2 = new Ch2_DVD('DVD', 'Atonement');
echo '<p>$product1 is a ' . $product1->getProductType();
echo ' called "' . $product1->getTitle() . '"</p>';
echo '<p>$product2 is a ' . $product2->getProductType();
echo ' called "' . $product2->getTitle() . '"</p>';
?>