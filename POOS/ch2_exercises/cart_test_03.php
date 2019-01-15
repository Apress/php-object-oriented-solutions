<?php
require_once '../Ch2/Book_05.php';
require_once '../Ch2/ShoppingCart_02.php';
require_once '../Ch2/DVD_03.php';

$book = new Ch2_Book('PHP Object-Oriented Solutions', 300);
$cart = new Ch2_ShoppingCart();
$cart->addItem($book);
$movie = new Ch2_DVD('Atonement', '2 hr 10 min');
$cart->addItem($movie);
?>