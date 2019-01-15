<?php
require_once '../Ch2/Book_05.php';
require_once '../Ch2/ShoppingCart_01.php';

$book = new Ch2_Book('PHP Object-Oriented Solutions', 300);
$cart = new Ch2_ShoppingCart();
$cart->addItem($book);
?>