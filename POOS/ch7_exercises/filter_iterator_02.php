<?php
class PriceFilter extends FilterIterator
{
	protected $_max;
	
	public function __construct($iterator, $maxPrice)
	{
		parent::__construct($iterator);
		$this->_max = (float) $maxPrice;
	}
	
	public function accept()
	{
		return substr($this->current(), 1) <= $this->_max;
	}
}
$xml = simplexml_load_file('inventory2.xml', 'SimpleXMLIterator');
foreach ($xml->book as $book) {
	foreach (new PriceFilter($book->price->paperback, 40) as $price) {
		echo "$book->title ($price)<br />";
	}
}
?>