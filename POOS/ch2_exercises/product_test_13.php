<?php
require_once '../Ch2/Book_08.php';

$book = new Ch2_Book('PHP Object-Oriented Solutions', 300);
$book->setManufacturerName('friends of ED');
echo '<p>' . $book->getTitle() . ' is manufactured by '. $book->getManufacturerName() . '</p>';
$book2 = clone $book;
$book2->setTitle('Website Disasters');
$book2->setManufacturerName('enemies of ED');
echo '<p>' . $book2->getTitle() . ' is manufactured by '. $book2->getManufacturerName() . '</p>';
echo '<p>' . $book->getTitle() . ' is manufactured by '. $book->getManufacturerName() . '</p>';
?>