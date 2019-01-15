<?php
require '../Ch2/Book_05.php';
require '../Ch2/DVD_03.php';

$book = new Ch2_Book('PHP Object-Oriented Solutions', 300);
$movie = new Ch2_DVD('Atonement', '2 hr 10 min');
$book->display();
$movie->display();
?>