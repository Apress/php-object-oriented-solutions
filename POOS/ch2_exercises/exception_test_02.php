<?php
require_once '../Ch2/Book_10.php';

try {
	$book = new Ch2_Book('PHP Object-Oriented Solutions', 'Wednesday');
	$book->display();
} catch (Exception $e) {
	echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
	echo '<p><strong>Code:</strong> ' . $e->getCode() . '</p>';
	echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
	echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
	echo '<p><strong>Trace:</strong> ';
	print_r($e->getTrace());
	echo '</p>';
	echo '<p><strong>Trace as string:</strong> ' . $e->getTraceAsString() . '</p>';
	echo '<p><strong>Using echo:</strong> ' . $e . '</p>';
}

?>