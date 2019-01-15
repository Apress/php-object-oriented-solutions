<?php
abstract class Ch2_Product
{
	// properties defined here
	protected $_type;
	protected $_title;
	
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