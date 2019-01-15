<?php

require_once 'Product_03.php';

class Ch2_Book extends Ch2_Product
{
	protected $_pageCount;
	
	public function __construct($type, $title, $pageCount)
	{
		parent::__construct($type, $title);
		$this->_pageCount = $pageCount; 
	}
	
	public function getPageCount()
	{
		return $this->_pageCount;
	}
}
