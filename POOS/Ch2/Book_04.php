<?php

require_once 'Product_03.php';

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
}
