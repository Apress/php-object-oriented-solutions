<?php
class Ch2_Product
{
	// properties defined here
	protected $_type;
	protected $_title;
	
	// constructor
	public  function __construct($type, $title)
	{
		$this->_type = $type;
		$this->_title = $title;
	}
	
	// methods defined here
	public function getProductType()
	{
		return $this->_type;
	}
	
	public function getTitle()
	{
		return $this->_title;
	}
}