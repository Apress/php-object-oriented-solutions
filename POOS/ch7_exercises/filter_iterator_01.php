<?php
class PriceFilter extends FilterIterator
{
	public function accept()
	{
		return substr($this->current(), 1) <= 40;
	}
}
$xml = simplexml_load_file('inventory2.xml', 'SimpleXMLIterator');
foreach ($xml->book as $book) {
	foreach (new PriceFilter($book->price->paperback) as $price) {
		echo "$book->title ($price)<br />";
	}
}
?>