<?php
require '../Ch2/Book.php';

try {
	$book = new Ch2_Book('PHP Object-Oriented Solutions', 'Wednesday');
	echo $book;
} catch (Exception $e) {
	echo '<p>$e->getMessage(): ' . $e->getMessage() . '</p>';
	echo '<p>$e->getCode(): ' . $e->getCode() . '</p>';
	echo '<p>$e->getFile(): ' . $e->getFile() . '</p>';
	echo '<p>$e->getLine(): ' . $e->getLine() . '</p>';
	echo '<p>$e->getTrace(): ';
	print_r($e->getTrace());
	echo '</p>';
	echo '<p>$e->getTraceAsString(): ' . $e->getTraceAsString() . '</p>';
	echo '<p>echo $e: ' . $e . '</p>';
}

?>