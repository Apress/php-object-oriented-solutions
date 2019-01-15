<?php

require_once 'Product_05.php';

class Ch2_Book extends Ch2_Product
{
	protected $_pageCount;
	
	public function __construct($title, $pageCount)
	{
		$this->_title = $title;
		$this->_pageCount = $pageCount; 
		$this->_type = 'book';
	}
	
	public function getPageCount()
	{
		return $this->_pageCount;
	}
	
	public function display()
	{
		echo "<p>Book: $this->_title ($this->_pageCount pages)</p>"; 
	}
}
