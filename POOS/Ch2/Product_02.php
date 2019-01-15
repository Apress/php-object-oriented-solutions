<?php
class Ch2_Product
{
	// properties defined here
	protected $_type = 'Book';
	
	// methods defined here
	public function getProductType()
	{
		return $this->_type;
	}
	
	public function setProductType($type)
	{
		$this->_type = $type;
	}
}